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
    
    /**
     * Permet le rendu d'une vue
     * @param $view
     * @param array $params
     * @return bool|array|string
     */
    public function render($view, array $params = []): bool|array|string
    {
        return Application::$app->view->renderView($view, $params);
    }
    
    /**
     * Initialise le layout de la page
     * @param $layout
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }
    
    /**
     * Ajouter un middleware au controller
     * @param BaseMiddleware $middleware
     */
    public function registerMiddleware(BaseMiddleware $middleware)
    {
        $this->middlewares[] = $middleware;
    }
    
    /**
     * Récupère tous les middlewares enregistrés dans un controller
     * @return BaseMiddleware[]
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}