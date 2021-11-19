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
    
    public function removeParams(string $path): string
    {
        return $this->dropSlash(preg_split("/:.+/", $path)[0]);
    }
    
    private function dropSlash(string $input): string
    {
        if ($input === "/") {
            return $input;
        }
        return preg_replace("/\/\//", "/", "/" . preg_replace("/(\/)$/", "", preg_replace("/^\//", "", $input)));
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
    
    private function getRecentGroup()
    {
        return $this->openedGroups[count($this->openedGroups) - 1] ?? [];
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
        $routes = $this->routes[$method];
        $params = [];
        if ($this->match($method, $path)) {
            if (count(array_slice(preg_split("/\//", $path), 1)) > 1) {
                $savePath = $path;
                $path = $this->match($method, $savePath);
                if (!$path) {
                    HttpError::e404();
                }
                foreach (preg_split("/\//", $path) as $key => $routeParams) {
                    if (in_array($routeParams, $this->params($path))) {
                        $params[substr($routeParams, 1)] = preg_split("/\//", $savePath)[$key];
                    }
                }
            }
        }
        $callback = $routes[$path] ?? false;
        if (!$callback) {
            HttpError::e404();
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
        return call_user_func($callback, $this->request, $this->response, $params);
    }
    
    public function match(string $method, string $path)
    {
        $pathSplitted = preg_split("/\//", $path);
        $matched = array_reduce(array_keys($this->routes[$method]), function($route, $curr) use ($pathSplitted) {
            if (str_starts_with($curr, "/" . $pathSplitted[1])) {
                array_push($route, $curr);
            }
            return $route;
        }, []);
        foreach ($matched as $match) {
            $matchSplitted = preg_split("/\//", $match);
            if (count($pathSplitted) === count($matchSplitted)) {
                if ($pathSplitted[count($pathSplitted) - 2] === $matchSplitted[count($matchSplitted) - 2]) {
                    $matchUrl = true;
                    foreach ($matchSplitted as $item) {
                        if(!in_array($item, $pathSplitted)) {
                            if(!preg_match("/:.+/", $item)) {
                                $matchUrl = false;
                            }
                        }
                    }
                    if($matchUrl) {
                        return $match;
                    }
                }
            }
        }
        return false;
    }
    
    public function params(string $path): array
    {
        return array_reduce(preg_split("/\//", $path), function($acc, $curr) {
            if (str_starts_with($curr, ":")) {
                array_push($acc, $curr);
            }
            return $acc;
        }, []);
    }
    
    public function group(string $prefix, callable $callback): self
    {
        array_push($this->openedGroups, $prefix);
        $callback();
        array_pop($this->openedGroups);
        return $this;
    }
}