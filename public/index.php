<?php
declare(strict_types=1);

use App\Controllers\AuthController;
use App\Controllers\ContactController;
use App\Core\Application;
use App\Models\User;
use Dotenv\Dotenv;

require_once __DIR__ . "/../vendor/autoload.php";
Dotenv::createImmutable(dirname(__DIR__))->load();

$config = [
    "userClass" => User::class,
    "db" => [
        "dsn" => $_ENV["DB_DSN"],
        "user" => $_ENV["DB_USER"],
        "password" => $_ENV["DB_PASSWORD"]
    ]
];

$app = new Application(dirname(__DIR__) . "/src", $config);

$router = $app->router;

$router->get("/", "home");

$router->get("/login", [AuthController::class, "login"]);
$router->post("/login", [AuthController::class, "login"]);

$router->get("/register", [AuthController::class, "register"]);
$router->post("/register", [AuthController::class, "register"]);

$router->get("/profile", [AuthController::class, "profile"]);

$router->get("/logout", [AuthController::class, "logout"]);

$router->get("/contact", [ContactController::class, "contact"]);

$app->run();
