<?php
declare(strict_types=1);

namespace App\Core\Exceptions;

use Exception;

class ForbiddenException extends Exception
{
    protected $message = "Tu n'as pas la permission d'accéder à cette page.";
    protected $code = 403;
}