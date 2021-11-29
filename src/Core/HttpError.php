<?php
declare(strict_types=1);

namespace App\Core;

use App\Core\Exceptions\Http\ForbiddenException;
use App\Core\Exceptions\Http\NotFoundException;
use App\Core\Exceptions\Http\UnauthorizedException;

class HttpError
{
    /**
     * Exception HTTP 401
     * @throws UnauthorizedException
     */
    public static function e401()
    {
        
        Application::$app->response->setStatusCode(401);
        throw new UnauthorizedException();
    }
    
    /**
     * Exception HTTP 403
     * @throws ForbiddenException
     */
    public static function e403()
    {
        Application::$app->response->setStatusCode(403);
        throw new ForbiddenException();
    }
    
    /**
     * Exception HTTP 404
     * @throws NotFoundException
     */
    public static function e404()
    {
        Application::$app->response->setStatusCode(404);
        throw new NotFoundException();
    }
}