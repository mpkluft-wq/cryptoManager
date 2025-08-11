<?php

declare(strict_types=1);

namespace App\Infrastructure\Exception;

use Symfony\Component\Messenger\Exception\RecoverableExceptionInterface;

/**
 * Base infrastructure error exception (database down etc.)
 */
class InfrastructureGenericException extends \RuntimeException implements RecoverableExceptionInterface
{
}
