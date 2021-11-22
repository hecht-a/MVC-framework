<?php
declare(strict_types=1);

namespace App\Middlewares;

use App\Core\Application;
use App\Core\BaseMiddleware;
use App\Core\Exceptions\ForbiddenException;

class AdminMiddleware extends BaseMiddleware
{
    
    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }
    
    /**
     * @throws ForbiddenException
     */
    public function execute()
    {
        if (!Application::isAdmin()) {
            if (empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)) {
                throw new ForbiddenException();
            }
        }
    }
}