<?php
declare(strict_types=1);

namespace App\Core;

class Controller
{
    public string $layout = "main";
    public string $action = "";
    
    /**
     * @var BaseMiddleware[]
     */
    protected array $middlewares = [];
    
    public function render($view, $params = []): bool|array|string
    {
        return Application::$app->view->renderView($view, $params);
    }
    
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }
    
    public function registerMiddleware(BaseMiddleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }
    
    /**
     * @return BaseMiddleware[]
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}