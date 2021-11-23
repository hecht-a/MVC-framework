<?php

namespace App\Core\Cli\Commands;

use App\Core\Application;
use App\Core\Cli\BaseCommand;
use App\Core\Controller;
use jc21\CliTable;

class CommandListRoutes extends BaseCommand
{
    public static string $commandName = "list:routes";
    public static string $description = "List routes";
    public static array $arguments = ["--method" => "filter routes list by method"];
    
    private Application $app;
    private array $args;
    
    public function __construct(Application $app, array $args)
    {
        $this->app = $app;
        $this->args = $args;
    }
    
    public function run()
    {
        $table = new CliTable();
        $table->setTableColor('blue');
        $table->setHeaderColor('cyan');
        $table->addField('Route', 'route', false, 'white');
        
        $method = $this->args["--method"] ?? null;
        $routes = $this->app->router->routes;
        $saveRoutes = [];
        if (!$method) {
            $table->addField('Method', 'method', false, 'grey');
            foreach ($routes as $key => $route) {
                foreach ($route as $k => $r) {
                    $c = explode("\\", $r[0]);
                    /** @var Controller|string $controller */
                    $controller = is_array($r) ? end($c) : "-";
                    $function = is_array($r) ? $r[1] : "-";
                    $middlewares = [];
                    if ($controller !== "-") {
                        $controller = new ("App\Controllers\\" . $controller)();
                        $middlewaresArray = $controller->getMiddlewares();
                        foreach ($middlewaresArray as $middleware) {
                            if (in_array($function, $middleware->actions)) {
                                $array = explode("\\", $middleware::class);
                                $middlewares[] = end($array);
                            }
                        }
                    }
                    $middlewares = count($middlewares) > 0 ? implode(", ", $middlewares) : "-";
                    $arr = [
                        "route" => $k,
                        "method" => $key,
                        "controller" => is_string($controller) ? "$controller" : end($c),
                        "function" => $function,
                        "middlewares" => $middlewares,
                        "view" => !is_array($r) ? $r : "-"
                    ];
                    $saveRoutes[] = $arr;
                }
            }
        } else {
            $method = strtolower($method);
            $saveRoutes = $this->onlyOne($routes[$method], $method);
        }
        
        $table->addField('Controller', 'controller', false, "white");
        $table->addField('Function', 'function', false, "white");
        $table->addField('Middlewares', 'middlewares', false, 'white');
        $table->addField('View', 'view', false, "white");
        $table->injectData($saveRoutes);
        $table->display();
    }
    
    private function onlyOne(array $routes, string $method)
    {
        $saveRoutes = [];
        foreach ($routes as $k => $r) {
            $c = explode("\\", $r[0]);
            /** @var Controller|string $controller */
            $controller = is_array($r) ? end($c) : "-";
            $function = is_array($r) ? $r[1] : "-";
            $middlewares = [];
            if ($controller !== "-") {
                $controller = new ("App\Controllers\\" . $controller)();
                $middlewaresArray = $controller->getMiddlewares();
                foreach ($middlewaresArray as $middleware) {
                    if (in_array($function, $middleware->actions)) {
                        $array = explode("\\", $middleware::class);
                        $middlewares[] = end($array);
                    }
                }
            }
            $middlewares = count($middlewares) > 0 ? implode(", ", $middlewares) : "-";
            $arr = [
                "route" => $k,
                "controller" => is_string($controller) ? "$controller" : end($c),
                "function" => $function,
                "middlewares" => $middlewares,
                "view" => !is_array($r) ? $r : "-"
            ];
            $saveRoutes[] = $arr;
        }
        return $saveRoutes;
    }
}