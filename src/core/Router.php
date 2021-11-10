<?php
declare(strict_types=1);

namespace App\Core;

use App\Core\Exceptions\NotFoundException;

class Router
{
    public Request $request;
    public Response $response;
    protected array $routes = [];
    private array $openedGroups = [];
    
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
    
    private function dropSlash(string $input): string
    {
        if ($input === "/") {
            return $input;
        }
        return preg_replace("/\/\//", "/", "/" . preg_replace("/(\/)$/", "", preg_replace("/^\//", "", $input)));
    }
    
    private function getRecentGroup()
    {
        return $this->openedGroups[count($this->openedGroups) - 1] ?? [];
    }
    
    public function get(string $path, $callback)
    {
        $openedGroup = $this->getRecentGroup();
        if ($openedGroup) {
            $this->routes["get"][$this->dropSlash("$openedGroup/" . $path)] = $callback;
        } else {
            $this->routes["get"][$path] = $callback;
        }
    }
    
    public function post(string $path, $callback)
    {
        $openedGroup = $this->getRecentGroup();
        if ($openedGroup) {
            $this->routes["post"][$this->dropSlash("$openedGroup/" . $path)] = $callback;
        } else {
            $this->routes["post"][$path] = $callback;
        }
    }
    
    /**
     * @throws NotFoundException
     */
    public function resolve()
    {
        $path = $this->dropSlash($this->request->getPath());
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;
        if (!$callback) {
            Application::$app->response->setStatusCode(404);
            throw new NotFoundException();
        }
        if (is_string($callback)) {
            return Application::$app->view->renderView($callback);
        }
        if (is_array($callback)) {
            /** @var Controller $controller */
            $controller = new $callback[0]();
            Application::$app->controller = $controller;
            $controller->action = $callback[1];
            $callback[0] = $controller;
            
            
            foreach ($controller->getMiddlewares() as $middleware) {
                $middleware->execute();
            }
        }
        return call_user_func($callback, $this->request, $this->response);
    }
    
    public function group(string $prefix, callable $callback): self
    {
        array_push($this->openedGroups, $prefix);
        $callback();
        array_pop($this->openedGroups);
        return $this;
    }
}