<?php
declare(strict_types=1);

namespace App\Core\Exceptions;

use Throwable;

class AlreadyUsedArgumentException extends \Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}