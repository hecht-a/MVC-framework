<?php

namespace App\Core\Cli;

use App\Core\Application;
use App\Core\Exceptions\AlreadyUsedArgumentException;

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
    
    /**
     * @throws AlreadyUsedArgumentException
     */
    public function parse()
    {
        $commandName = $this->arguments[0] ?? [];
        $arguments = $this->parseArguments(array_splice($this->arguments, 1));
        $commands = array_map(fn($cmd) => $cmd["command"], BaseCommand::getCommands());
        if (in_array($commandName, $commands)) {
            $className = [...array_filter(BaseCommand::getCommands(), fn($command) => $command["command"] === $commandName)][0]["class"];
            /** @var BaseCommand $class */
            $class = new ("App\Core\Cli\Commands\\$className")($this->app, $arguments);
            $class->run();
            return;
        }
        echo "Command introuvable, essayez bin/console help\n";
    }
    
    /**
     * @throws AlreadyUsedArgumentException
     */
    private function parseArguments(array $arguments): array
    {
        $args = [];
        foreach ($arguments as $key => $argument) {
            if (!in_array(strval($argument), array_values($args))) {
                if (str_starts_with($argument, "--")) {
                    if(in_array($argument, array_keys($args))) {
                        throw new AlreadyUsedArgumentException("L'argument $argument a déjà été donné.");
                    }
                    $args[$argument] = $arguments[$key + 1] ?? null;
                } else {
                    $args[] = $argument;
                }
            }
        }
        return $args;
    }
}