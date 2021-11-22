<?php

namespace App\Core\Cli\Commands;

use App\Core\Application;
use App\Core\Cli\BaseCommand;

class CommandMakeModel extends BaseCommand
{
    public static string $commandName = "make:model";
    public static string $description = "make a model";
    
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
            echo "Le nom du model doit être spécifié\n";
            return;
        }
        $name = ucfirst(strtolower($this->args[0]));
        $modelsFolder = dirname(__DIR__) . "/../../Models";
        if (file_exists("$modelsFolder/$name.php")) {
            echo "Le model $name existe déjà" . PHP_EOL;
            return;
        }
        $file = fopen("$modelsFolder/$name.php", "w");
        $content = file_get_contents($this->template("model.txt"));
        $content = preg_replace("/{{ modelname }}/", $name, $content);
        fwrite($file, $content);
        fclose($file);
        echo "Le model $name a été créé" . PHP_EOL;
    }
    
    public function template(string $file): string
    {
        $templatesFolder = dirname(__DIR__) . "/../../templates";
        return "$templatesFolder/$file";
    }
}