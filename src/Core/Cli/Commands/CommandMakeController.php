<?php

namespace App\Core\Cli\Commands;

use App\Core\Application;
use App\Core\Cli\BaseCommand;

class CommandMakeController extends BaseCommand
{
    public static string $commandName = "make:controller";
    public static string $description = "make a controller";
    
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
            echo "Le nom du controller doit être spécifié\n";
            return;
        }
        $name = ucfirst(strtolower($this->args[0])) . "Controller";
        $controllersFolder = dirname(__DIR__) . "/../../Controllers";
        if (file_exists("$controllersFolder/$name.php")) {
            echo "Le controller $name existe déjà" . PHP_EOL;
            return;
        }
        $file = fopen("$controllersFolder/$name.php", "w");
        $content = file_get_contents($this->template("controller.txt"));
        $content = preg_replace("/{{ classname }}/", $name, $content);
        fwrite($file, $content);
        fclose($file);
        echo "Le controller $name a été créé" . PHP_EOL;
    }
    
    public function template(string $file): string
    {
        $templatesFolder = dirname(__DIR__) . "/../templates";
        return "$templatesFolder/$file";
    }
}