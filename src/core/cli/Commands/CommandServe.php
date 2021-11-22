<?php
declare(strict_types=1);

namespace App\core\cli\Commands;

use App\Core\Application;
use App\Core\Cli\BaseCommand;

class CommandServe extends BaseCommand
{
    private array $chars = [
        'top' => '═',
        'bottom' => '═',
        'top-left' => '╔',
        'top-right' => '╗',
        'bottom-left' => '╚',
        'bottom-right' => '╝',
        'left' => '║',
        'right' => '║',
    ];
    public static string $commandName = "serve";
    public static string $description = "Launch development server";
    
    private Application $app;
    private array $args;
    
    public function __construct(Application $app, array $args = [])
    {
        $this->app = $app;
        $this->args = $args;
    }
    
    public function run()
    {
        $port = 8000;
        $data = [["test" => "test"]];
        echo "Starting server..." . PHP_EOL;
        $publicFolder = dirname(__DIR__) . "/../../../public";
        echo $this->showInfos($port);
        exec("php -S localhost:$port -t $publicFolder");
    }
    
    public function showInfos(int $port): string
    {
        $infos = str_repeat(" ", 5) . "Server address: http://localhost:$port" . str_repeat(" ", 5);
        
        $blank = $this->chars["left"] . str_repeat(" ", strlen($infos)) . $this->chars["right"] . PHP_EOL;
        
        $str = $this->chars["top-left"] . str_repeat($this->chars["top"], strlen($infos)) . $this->chars["top-right"] . PHP_EOL;
        $str .= $blank;
        $str .= $this->chars["left"] . $infos . $this->chars["right"] . PHP_EOL;
        $str .= $blank;
        $str .= $this->chars["bottom-left"] . str_repeat($this->chars["top"], strlen($infos)) . $this->chars["bottom-right"] . PHP_EOL;
        return $str;
    }
}