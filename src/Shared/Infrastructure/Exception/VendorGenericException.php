<?php

declare(strict_types=1);

namespace App\Infrastructure\Exception;

use Symfony\Component\Messenger\Exception\RecoverableExceptionInterface;

/**
 * Use this exception in case of some vendor error, which is not related to hardware (network, io, etc)
 * For example, if database is down this is NOT vendor error.
 * If you are trying to ask vendor library to fetch data by specific condition, but they are empty  -
 * then it might be your case.
 */
class VendorGenericException extends \Exception implements RecoverableExceptionInterface
{
}
