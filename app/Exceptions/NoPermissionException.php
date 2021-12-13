<?php


namespace App\Exceptions;

use Exception;
use Throwable;

class NoPermissionException extends Exception
{
    public function __construct($message = "no permission", $code = 403, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
