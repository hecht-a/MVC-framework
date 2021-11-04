<?php
declare(strict_types=1);

namespace App\Core;

use http\Params;

class Router
{
    public Request $request;
    public Response $response;
    protected array $routes = [];
    
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
    
    public function get(string $path, $callback)
    {
        $this->routes["get"][$path] = $callback;
    }
    
    public function post(string $path, $callback)
    {
        $this->routes["post"][$path] = $callback;
    }
    
    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;
        if (!$callback) {
            Application::$app->response->setStatusCode(404);
            return $this->renderView("_404");
        }
        if (is_string($callback)) {
            return $this->renderView($callback);
        }
        if (is_array($callback)) {
            Application::$app->controller = new $callback[0]();
            $callback[0] = Application::$app->controller;
        }
        return call_user_func($callback, $this->request, $this->response);
    }
    
    public function renderView(string $view, $params = []): array|bool|string
    {
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view, $params);
        $content = str_replace("{{content}}", $viewContent, $layoutContent);
        
        if(Application::$app->session->get("user")) {
            $content = str_replace("{{user}}", "<li><a href='/profile'>profil</a></li><li><a href='/logout'>déconnexion</a></li>", $content);
        } else {
            $content = str_replace("{{user}}", "<li><a href='/login'>connexion</a></li>", $content);
        }
        
        if (Application::$app->session->getFlash("success")) {
            $content = str_replace("{{flashMessages}}", "<div class='alert alert-success absolute'>" . Application::$app->session->getFlash("success") . " </div>", $content);
        }
        return preg_replace("/.*({{.*}}).*/", "", $content);
    }
    
    public function renderContent($viewContent): array|bool|string
    {
        $layoutContent = $this->layoutContent();
        $content = str_replace("{{content}}", $viewContent, $layoutContent);
        return preg_replace("/.*({{.*}}).*/", "", $content);
    }
    
    protected function layoutContent(): bool|string
    {
        $layout = Application::$app->controller->layout ?? "main";
        ob_start();
        include_once Application::$ROOT_DIR . "/views/layouts/$layout.php";
        return ob_get_clean();
    }
    
    protected function renderOnlyView($view, $params): bool|string
    {
        ob_start();
        include_once Application::$ROOT_DIR . "/views/$view.php";
        $content = ob_get_clean();
        foreach ($params as $key => $value) {
            $content = str_replace("{{" . $key . "}}", strval($value), $content);
        }
        return preg_replace("/.*({{.*}}).*/", "", $content);
    }
}