<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Exception;

/**
 * Base infrastructure temporary exception (usually this exception is used when the operation must be retried).
 * For example if some data is not yet available due to a race condition, and further calls might be successful.
 */
class InfrastructureTemporaryException extends \RuntimeException
{
}
