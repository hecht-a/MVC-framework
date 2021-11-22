<?php
declare(strict_types=1);

namespace App\Core\Exceptions\Http;

use Exception;

class NotFoundException extends Exception
{
    protected $message = "Cette page n'existe pas.";
    protected $code = 404;
}