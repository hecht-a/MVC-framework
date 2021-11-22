<?php

namespace App\Core\Cli\Commands;

use App\Core\Application;
use App\Core\Cli\BaseCommand;
use jc21\CliTable;

class CommandHelp extends BaseCommand
{
    public static string $commandName = "help";
    public static string $description = "Show commands help";
    
    private Application $app;
    
    public function __construct(Application $app)
    {
        $this->app = $app;
    }
    
    public function run()
    {
        $help = [];
        $dir = scandir(dirname(__DIR__) . "/Commands");
        $files = array_map(fn($file) => explode(".", $file)[0], preg_grep("/^Command.+(.php)$/", $dir));
        foreach ($files as $file) {
            /** @var BaseCommand $class */
            $class = ("App\Core\Cli\Commands\\" . $file);
            $help[] = ["name" => $class::$commandName, "description" => $class::$description];
        }
        $table = new CliTable();
        $table->setTableColor('blue');
        $table->setHeaderColor('cyan');
        $table->addField('Name', 'name', false, "white");
        $table->addField('Description', 'description', false, "white");
        $table->injectData($help);
        $table->display();
    }
}