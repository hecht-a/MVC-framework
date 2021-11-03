<?php
declare(strict_types=1);

use App\Controllers\AuthController;
use App\Core\Application;
use Dotenv\Dotenv;

require_once __DIR__ . "/../vendor/autoload.php";
Dotenv::createImmutable(dirname(__DIR__))->load();

$config = [
    "db" => [
        "dsn" => $_ENV["DB_DSN"],
        "user" => $_ENV["DB_USER"],
        "password" => $_ENV["DB_PASSWORD"]
    ]
];

$app = new Application(dirname(__DIR__) . "/src", $config);

$app->router->get("/", "home");

$app->router->get("/login", [AuthController::class, "login"]);
$app->router->post("/login", [AuthController::class, "login"]);

$app->router->get("/register", [AuthController::class, "register"]);
$app->router->post("/register", [AuthController::class, "register"]);
$app->run();
