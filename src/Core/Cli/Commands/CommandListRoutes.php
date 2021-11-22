<?php

namespace App\Core\Cli\Commands;

use App\Core\Application;
use App\Core\Cli\BaseCommand;
use jc21\CliTable;

class CommandListRoutes extends BaseCommand
{
    public static string $commandName = "list:routes";
    public static string $description = "List routes";
    
    private Application $app;
    
    public function __construct(Application $app, array $args)
    {
        $this->app = $app;
    }
    
    public function run()
    {
        $routes = $this->app->router->routes;
        $saveRoutes = [];
        foreach ($routes as $key => $route) {
            foreach ($route as $k => $r) {
                $c = explode("\\", $r[0]);
                $arr = [
                    "route" => $k,
                    "method" => $key,
                    "controller" => is_array($r) ? end($c) : "-",
                    "function" => is_array($r) ? $r[1] : "-",
                    "view" => !is_array($r) ? $r : "-"
                ];
                $saveRoutes[] = $arr;
            }
        }
        
        $table = new CliTable();
        $table->setTableColor('blue');
        $table->setHeaderColor('cyan');
        $table->addField('Route', 'route', false, 'white');
        $table->addField('Method', 'method', false, 'grey');
        $table->addField('Controller', 'controller', false, "white");
        $table->addField('Function', 'function', false, "white");
        $table->addField('View', 'view', false, "white");
        $table->injectData($saveRoutes);
        $table->display();
    }
}