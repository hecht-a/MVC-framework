<?php

namespace App\Core\Cli;

use App\Core\Application;

abstract class BaseCommand
{
    public static string $commandName;
    public static string $description;
    
    abstract public function __construct(Application $app, array $args);
    
    public static function getCommands(): array
    {
        $dir = scandir(dirname(__DIR__) . "/cli/Commands");
        $files = array_map(fn($file) => explode(".", $file)[0], preg_grep("/^Command.+(.php)$/", $dir));
        return array_map(function($file) {
            $commandName = ("App\Core\Cli\Commands\\" . $file)::$commandName;
            return ["command" => $commandName, "class" => $file];
        }, $files);
    }
    
    abstract public function run();
}