<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Controller\CryptoCurrency;

use App\CryptoCurrency\Domain\DTO\CryptoCurrencyFilterDTO;
use App\CryptoCurrency\Domain\Entity\CryptoCurrency;
use App\CryptoCurrency\Domain\Repository\CryptoCurrencyReadRepositoryInterface;
use App\CryptoCurrency\Domain\Repository\CryptoCurrencyWriteRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class GetAllController extends AbstractController
{
    public function __construct(
        private readonly CryptoCurrencyReadRepositoryInterface $cryptoCurrencyReadRepository,
        private readonly CryptoCurrencyWriteRepositoryInterface $cryptoCurrencyWriteRepository
    ) {
    }

    public function __invoke(Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            return new Response('isXmlHttpRequest');
        }

        // Обработка POST-запроса для создания новой криптовалюты
        if ($request->isMethod('POST')) {
            $this->processCryptoCurrencyForm($request);
            
            // Редирект на ту же страницу чтобы избежать повторной отправки формы при обновлении
            return $this->redirectToRoute('crypto_currency_list');
        }

        // Получаем все криптовалюты
        $filterDTO = new CryptoCurrencyFilterDTO(
            null,
            0,
            100,
            ['symbol' => 'ASC']
        );
        
        $cryptoCurrencies = $this->cryptoCurrencyReadRepository->findByFilter($filterDTO);
        
        return $this->render(
            '/crypto_currency/list.html.twig',
            [
                'cryptoCurrencies' => $cryptoCurrencies,
            ],
        );
    }
    
    private function processCryptoCurrencyForm(Request $request): void
    {
        // Получаем данные из формы
        $symbol = $request->request->get('symbol');
        $name = $request->request->get('name');
        $isStablecoin = (bool) $request->request->get('isStablecoin');
        $decimals = (int) $request->request->get('decimals');
        $logoPath = null; // Логотип пока не реализован
        $launchYear = $request->request->get('launchYear') ? (int) $request->request->get('launchYear') : null;
        $projectUrl = $request->request->get('projectUrl') ?: null;
        $explorerUrl = $request->request->get('explorerUrl') ?: null;
        $blockchainType = $request->request->get('blockchainType');
        $network = $request->request->get('network') ?: null;
        $contractAddress = $request->request->get('contractAddress') ?: null;
        
        // Создаем новую криптовалюту
        $cryptoCurrency = new CryptoCurrency(
            $symbol,
            $name,
            $isStablecoin,
            $decimals,
            $logoPath,
            $launchYear,
            $projectUrl,
            $explorerUrl,
            $blockchainType,
            $network,
            $contractAddress,
            new \DateTimeImmutable()
        );
        
        // Сохраняем в базу данных
        $this->cryptoCurrencyWriteRepository->save($cryptoCurrency);
        $this->cryptoCurrencyWriteRepository->flush();
        
        // Добавляем флеш-сообщение об успехе
        $this->addFlash('success', "Криптовалюта {$symbol} успешно добавлена");
    }
}
