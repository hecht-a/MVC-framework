<?php
declare(strict_types=1);

setlocale(LC_ALL, 'fr_FR');

use App\Controllers\AdminController;
use App\Controllers\AuthController;
use App\Controllers\ConsultationController;
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
$router->get("/logout", [AuthController::class, "logout"]);

$router->group("/profile", function() use ($router){
    $router->get("", [AuthController::class, "profile"]);
    
    $router->get("/consultations", [ConsultationController::class, "list"]);
    $router->get("/consultation/delete/:id", [ConsultationController::class, "delete"]);
    $router->get("/consultation/edit/:id", [ConsultationController::class, "index"]);
    $router->post("/consultation/edit/:id", [ConsultationController::class, "edit"]);
    $router->get("/consultation/:id", [ConsultationController::class, "show"]);
});

$router->get("/contact", [ContactController::class, "index"]);

$router->get("/consultation", [ConsultationController::class, "index"]);
$router->post("/consultation", [ConsultationController::class, "add"]);

$router->group("/admin", function() use ($router) {
    $router->get("", [AdminController::class, "index"]);
    $router->get("/consultations", [AdminController::class, "consultations"]);
    $router->get("/users", [AdminController::class, "users"]);
    $router->post("/users", [AdminController::class, "changeAdmin"]);
});

$app->run();
