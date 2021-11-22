<?php

namespace App\Core\Cli;

use App\Core\Application;

class ArgvParser
{
    private Application $app;
    private array $arguments = [];
    
    public function __construct(Application $app)
    {
        $this->app = $app;
        array_shift($_SERVER["argv"]);
        $this->arguments = $_SERVER["argv"];
    }
    
    public function parse()
    {
        $commandName = $this->arguments[0] ?? [];
        $commands = array_map(fn($cmd) => $cmd["command"], BaseCommand::getCommands());
        if (in_array($commandName, $commands)) {
            $className = [...array_filter(BaseCommand::getCommands(), fn($command) => $command["command"] === $commandName)][0]["class"];
            /** @var BaseCommand $class */
            $class = new ("App\Core\Cli\Commands\\$className")($this->app, array_splice($this->arguments, 1));
            $class->run();
            return;
        }
        echo "Command introuvable, essayez bin/console help\n";
    }
}