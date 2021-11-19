<?php
declare(strict_types=1);

namespace App\Core;

use App\Core\Exceptions\ForbiddenException;
use App\Core\Exceptions\NotFoundException;
use App\Core\Exceptions\UnauthorizedException;

class HttpError
{
    /**
     * @throws UnauthorizedException
     */
    public static function e401()
    {
        
        Application::$app->response->setStatusCode(401);
        throw new UnauthorizedException();
    }
    
    /**
     * @throws ForbiddenException
     */
    public static function e403()
    {
        Application::$app->response->setStatusCode(403);
        throw new ForbiddenException();
    }
    
    /**
     * @throws NotFoundException
     */
    public static function e404()
    {
        Application::$app->response->setStatusCode(404);
        throw new NotFoundException();
    }
}