<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Controller\Transaction;

use App\Transaction\Domain\Repository\TransactionReadRepositoryInterface;
use App\Transaction\Domain\Repository\TransactionWriteRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Контроллер для удаления транзакции по её идентификатору
 */
final class DeleteController extends AbstractController
{
    public function __construct(
        private readonly TransactionReadRepositoryInterface $transactionReadRepository,
        private readonly TransactionWriteRepositoryInterface $transactionWriteRepository,
    ) {
    }

    public function __invoke(Request $request, string $id): Response
    {
        $transaction = $this->transactionReadRepository->findById($id);
        if ($transaction === null) {
            $this->addFlash('error', 'Транзакция не найдена');
            return $this->redirectToRoute('transaction_list');
        }

        try {
            $this->transactionWriteRepository->delete($transaction);
            $this->transactionWriteRepository->flush();
            $this->addFlash('success', 'Транзакция удалена');
        } catch (\Throwable $e) {
            $this->addFlash('error', 'Не удалось удалить: ' . $e->getMessage());
        }

        return $this->redirectToRoute('transaction_list');
    }
}
