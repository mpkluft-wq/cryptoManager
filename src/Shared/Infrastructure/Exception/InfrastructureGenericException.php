<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Exception;

/**
 * Base infrastructure error exception (database down etc.)
 */
class InfrastructureGenericException extends \RuntimeException
{
}
