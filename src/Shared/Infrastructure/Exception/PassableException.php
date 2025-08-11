<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Exception;

/**
 * Passable exception, which is different to infrastructure or vendor exception and should be passed thru.
 */
class PassableException extends \RuntimeException
{
}
