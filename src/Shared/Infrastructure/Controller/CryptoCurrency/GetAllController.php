<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Controller\CryptoCurrency;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class GetAllController extends AbstractController
{
    public function __construct(
    ) {
    }

    public function __invoke(Request $request): Response
    {
        if ($request->isXmlHttpRequest()) {
            return new Response('isXmlHttpRequest');
        }

        return $this->render(
            '/crypto_currency/list.html.twig',
            [
            ],
        );
    }
}
