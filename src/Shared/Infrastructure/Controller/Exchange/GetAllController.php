<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Controller\Exchange;

use App\Exchange\Domain\DTO\ExchangeFilterDTO;
use App\Exchange\Domain\Entity\Exchange;
use App\Exchange\Domain\Repository\ExchangeReadRepositoryInterface;
use App\Exchange\Domain\Repository\ExchangeWriteRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class GetAllController extends AbstractController
{
    public function __construct(
        private readonly ExchangeReadRepositoryInterface $exchangeReadRepository,
        private readonly ExchangeWriteRepositoryInterface $exchangeWriteRepository,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            return $this->handlePostRequest($request);
        }

        if ($request->isXmlHttpRequest()) {
            return new Response('isXmlHttpRequest');
        }

        $page = max(1, (int)$request->query->get('page', 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $filter = new ExchangeFilterDTO(
            null,
            $offset,
            $limit
        );

        $exchanges = $this->exchangeReadRepository->findByFilter($filter);
        $total = $this->exchangeReadRepository->countByFilter($filter);
        $totalPages = ceil($total / $limit);

        return $this->render(
            '/exchange/list.html.twig',
            [
                'exchanges' => $exchanges,
                'currentPage' => $page,
                'totalPages' => $totalPages,
                'total' => $total,
            ],
        );
    }

    private function handlePostRequest(Request $request): Response
    {
        $name = $request->request->get('name');
        $apiUrl = $request->request->get('apiUrl');
        $webUrl = $request->request->get('webUrl');
        $rateLimit = (int)$request->request->get('rateLimit');
        $tradingFees = $request->request->get('tradingFees');
        $logoPath = $request->request->get('logoPath');
        $isEnabled = (bool)$request->request->get('isEnabled', false);
        
        try {
            $exchange = new Exchange(
                $name,
                $isEnabled,
                $apiUrl,
                $webUrl,
                $logoPath,
                $rateLimit,
                $tradingFees,
                new \DateTimeImmutable()
            );
            
            $this->exchangeWriteRepository->save($exchange);
            $this->exchangeWriteRepository->flush();
            
            $this->addFlash('success', 'Биржа успешно добавлена');
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Ошибка при добавлении биржи: ' . $e->getMessage());
        }
        
        return $this->redirectToRoute('exchange_list');
    }
}
