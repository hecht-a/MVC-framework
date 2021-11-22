<?php
declare(strict_types=1);

namespace App\core\cli\Commands;

use App\Core\Application;

class CommandMigrate extends \App\Core\Cli\BaseCommand
{
    public static string $commandName = "migrate";
    public static string $description = "Launch migrations";
    
    private Application $app;
    
    public function __construct(Application $app)
    {
        $this->app = $app;
    }
    
    public function run()
    {
        $this->app->db->applyMigrations();
    }
}