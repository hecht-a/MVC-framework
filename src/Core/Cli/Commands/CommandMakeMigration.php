<?php

namespace App\Core\Cli\Commands;

use App\Core\Application;
use App\Core\Cli\BaseCommand;

class CommandMakeMigration extends BaseCommand
{
    public static string $commandName = "make:migration";
    public static string $description = "make a migration";
    public static array $arguments = [];
    
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
            echo "Le nom de la migration doit être spécifié\n";
            return;
        }
        $array = scandir(dirname(__DIR__) . "/../../migrations");
        $number = $this->addZeros($this->getMigrationNumber(end($array)));
        $name = "m$number" . "_" . strtolower($this->args[0]);
        $migrationsFolder = dirname(__DIR__) . "/../../migrations";
        $file = fopen("$migrationsFolder/$name.php", "w");
        $content = file_get_contents($this->template("migration.txt"));
        $content = preg_replace("/{{ migrationname }}/", $name, $content);
        fwrite($file, $content);
        fclose($file);
        echo "La migration $name a été créée" . PHP_EOL;
    }
    
    public function template(string $file): string
    {
        $templatesFolder = dirname(__DIR__) . "/../templates";
        return "$templatesFolder/$file";
    }
    
    private function getMigrationNumber(string $name): int
    {
        return intval(substr(explode("_", $name)[0], 1)) + 1;
    }
    
    private function addZeros(int $number): string
    {
        return str_repeat("0", 4 - strlen(strval($number))) . $number;
    }
}