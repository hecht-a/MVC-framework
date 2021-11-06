<?php
declare(strict_types=1);

namespace App\Core\Middlewares;

use App\Core\Application;

class ConsultationMiddleware extends BaseMiddleware
{
    public array $actions = [];
    
    public function __construct(array $actions = [])
    {
        $this->actions = $actions;
    }
    
    public function execute()
    {
        if (Application::isGuest()) {
            if (empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)) {
                Application::$app->response->redirect("/login");
            }
        }
    }
}