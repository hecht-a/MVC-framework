<?php

use App\Controllers\AdminController;
use App\Controllers\AnimalsController;
use App\Controllers\AuthController;
use App\Controllers\ConsultationController;
use App\Controllers\ContactController;
use App\Core\Application;
use App\Models\User;
use Dotenv\Dotenv;

require_once __DIR__ . "/../../vendor/autoload.php";

Dotenv::createImmutable(dirname(__DIR__) . "/../")->load();

$config = [
    "userClass" => User::class,
    "db" => [
        "dsn" => $_ENV["DB_DSN"],
        "user" => $_ENV["DB_USER"],
        "password" => $_ENV["DB_PASSWORD"]
    ]
];
$app = new Application(dirname(__DIR__) . "/../src", $config);

$router = $app->router;

$router->get("/", "home");

$router->get("/login", [AuthController::class, "login"]);
$router->post("/login", [AuthController::class, "login"]);
$router->get("/register", [AuthController::class, "register"]);
$router->post("/register", [AuthController::class, "register"]);
$router->get("/logout", [AuthController::class, "logout"]);

$router->group("/profile", function() use ($router) {
    $router->get("", [AuthController::class, "profile"]);
    $router->get("/animals", [AnimalsController::class, "list"]);
    $router->get("/consultations", [ConsultationController::class, "list"]);
    
    $router->group("/animal", function() use ($router) {
        $router->get("/:id", [AnimalsController::class, "index"]);
        $router->get("/edit/:id", [AnimalsController::class, "edit"]);
        $router->post("/edit/:id", [AnimalsController::class, "edit"]);
    });
    
    $router->group("/consultation", function() use ($router) {
        $router->get("/delete/:id", [ConsultationController::class, "delete"]);
        $router->get("/edit/:id", [ConsultationController::class, "index"]);
        $router->post("/edit/:id", [ConsultationController::class, "edit"]);
        $router->get("/:id", [ConsultationController::class, "show"]);
    });
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

return $app;