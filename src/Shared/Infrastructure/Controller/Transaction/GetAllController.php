<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Controller\Transaction;

use App\CryptoCurrency\Domain\DTO\CryptoCurrencyFilterDTO;
use App\CryptoCurrency\Domain\Repository\CryptoCurrencyReadRepositoryInterface;
use App\Exchange\Domain\DTO\ExchangeFilterDTO;
use App\Exchange\Domain\Repository\ExchangeReadRepositoryInterface;
use App\Transaction\Domain\DTO\TransactionFilterDTO;
use App\Transaction\Domain\Entity\Transaction;
use App\Transaction\Domain\Enumeration\TransactionType;
use App\Transaction\Domain\Repository\TransactionReadRepositoryInterface;
use App\Transaction\Domain\Repository\TransactionWriteRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Контроллер для отображения списка транзакций и создания новой транзакции.
 */
final class GetAllController extends AbstractController
{
    public function __construct(
        private readonly TransactionReadRepositoryInterface $transactionReadRepository,
        private readonly TransactionWriteRepositoryInterface $transactionWriteRepository,
        private readonly ExchangeReadRepositoryInterface $exchangeReadRepository,
        private readonly CryptoCurrencyReadRepositoryInterface $cryptoCurrencyReadRepository,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            return new Response('isXmlHttpRequest');
        }

        // Обработка POST-запроса для создания новой транзакции
        if ($request->isMethod('POST')) {
            $error = $this->processTransactionForm($request);
            if ($error !== null) {
                $this->addFlash('error', $error);
            } else {
                $this->addFlash('success', 'Транзакция успешно добавлена');
            }

            return $this->redirectToRoute('transaction_list');
        }

        // Получаем все транзакции (без фильтров, базовая пагинация)
        $filterDTO = new TransactionFilterDTO(
            null,
            null,
            0,
            100,
            ['timestamp' => 'DESC']
        );

        $transactions = $this->transactionReadRepository->findByFilter($filterDTO);

        // Получаем список бирж для выпадающего списка
        $exchanges = $this->exchangeReadRepository->findByFilter(new ExchangeFilterDTO(
            null,
            0,
            1000,
            ['name' => 'ASC']
        ));

        // Получаем список всех активов из справочника CryptoCurrency
        $ccFilter = new CryptoCurrencyFilterDTO(
            null,
            0,
            1000,
            ['symbol' => 'ASC']
        );
        $cryptoCurrencies = $this->cryptoCurrencyReadRepository->findByFilter($ccFilter);
        $assetSymbols = array_map(static fn($cc) => $cc->getSymbol(), $cryptoCurrencies);

        // Построим карту id биржи -> название для использования в шаблоне
        $exchangeNamesById = [];
        foreach ($exchanges as $ex) {
            // Используем только геттеры, чтобы не обращаться к приватным свойствам
            if (method_exists($ex, 'getId') && method_exists($ex, 'getName')) {
                $exchangeNamesById[$ex->getId()] = $ex->getName();
            }
        }

        return $this->render(
            '/transaction/list.html.twig',
            [
                'transactions' => $transactions,
                'transactionTypes' => array_map(static fn($case) => $case->value, TransactionType::cases()),
                'exchanges' => $exchanges,
                'exchangeNamesById' => $exchangeNamesById,
                'assetSymbols' => $assetSymbols,
            ],
        );
    }

    private function processTransactionForm(Request $request): ?string
    {
        // Минимальная валидация и парсинг
        $timestampStr = (string)($request->request->get('timestamp') ?? '');
        $typeStr = (string)($request->request->get('type') ?? '');
        $exchangeId = trim((string)($request->request->get('exchangeId') ?? ''));
        $tradingBotId = trim((string)($request->request->get('tradingBotId') ?? '')) ?: null;
        $assetSymbol = trim((string)($request->request->get('assetSymbol') ?? ''));
        $quantityStr = (string)($request->request->get('quantity') ?? '');
        $pricePerUnitStr = (string)($request->request->get('pricePerUnit') ?? '');
        // Валюта вводится автоматически, поле на форме отсутствует
        $currency = 'USD';
        $externalTransactionId = trim((string)($request->request->get('externalTransactionId') ?? '')) ?: null;
        $notes = trim((string)($request->request->get('notes') ?? '')) ?: null;

        // Контекст попытки добавления для сообщений об ошибке
        $context = sprintf(' [assetSymbol="%s", exchangeId="%s"]', $assetSymbol, $exchangeId);

        // timestamp: допускаем пустое значение -> текущее время
        // Поддержка значения из <input type="datetime-local">: заменяем 'T' на пробел;
        // если секунда отсутствует (формат YYYY-MM-DD HH:MM), добавляем ":00".
        if ($timestampStr !== '') {
            $normalized = str_replace('T', ' ', $timestampStr);
            if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $normalized) === 1) {
                $normalized .= ':00';
            }
            $timestamp = date_create_immutable($normalized);
        } else {
            $timestamp = new \DateTimeImmutable();
        }
        if (!$timestamp instanceof \DateTimeImmutable) {
            return 'Некорректный формат даты/времени' . $context;
        }

        // enum TransactionType
        try {
            $type = TransactionType::from($typeStr);
        } catch (\ValueError) {
            return 'Некорректный тип транзакции' . $context;
        }

        if ($exchangeId === '' || $assetSymbol === '') {
            return 'Поля exchangeId и assetSymbol обязательны' . $context;
        }

        // числа
        if (!is_numeric($quantityStr) || !is_numeric($pricePerUnitStr)) {
            return 'Поля quantity и pricePerUnit должны быть числами' . $context;
        }

        $quantity = (float)$quantityStr;
        $pricePerUnit = (float)$pricePerUnitStr;
        $totalAmount = $quantity * $pricePerUnit;

        // Создание и сохранение
        $transaction = new Transaction(
            $timestamp,
            $type,
            $exchangeId,
            $tradingBotId,
            $assetSymbol,
            $quantity,
            $pricePerUnit,
            $totalAmount,
            $currency,
            $externalTransactionId,
            $notes
        );

        try {
            $this->transactionWriteRepository->save($transaction);
            $this->transactionWriteRepository->flush();
        } catch (\Throwable $e) {
            return 'Не удалось добавить транзакцию: ' . $e->getMessage() . $context;
        }

        return null;
    }
}
