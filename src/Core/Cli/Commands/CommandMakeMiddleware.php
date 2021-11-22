<?php

namespace App\Core\Cli\Commands;

use App\Core\Application;
use App\Core\Cli\BaseCommand;

class CommandMakeMiddleware extends BaseCommand
{
    public static string $commandName = "make:middleware";
    public static string $description = "make a middleware";
    
    private Application $app;
    private array $args;
    
    public function __construct(Application $app, array $args)
    {
        $this->app = $app;
        $this->args = $args;
    }
    
    public function run()
    {
        if (!isset($this->args[0])) {
            echo "Le nom du middleware doit être spécifié\n";
            return;
        }
        $name = ucfirst(strtolower($this->args[0])) . "Middleware";
        $middlewaresFolder = dirname(__DIR__) . "/../../Middlewares";
        if (file_exists("$middlewaresFolder/$name.php")) {
            echo "Le middleware $name existe déjà" . PHP_EOL;
            return;
        }
        $file = fopen("$middlewaresFolder/$name.php", "w");
        $content = file_get_contents($this->template("middleware.txt"));
        $content = preg_replace("/{{ middlewarename }}/", $name, $content);
        fwrite($file, $content);
        fclose($file);
        echo "Le middleware $name a été créé" . PHP_EOL;
    }
    
    public function template(string $file): string
    {
        $templatesFolder = dirname(__DIR__) . "/../templates";
        return "$templatesFolder/$file";
    }
}