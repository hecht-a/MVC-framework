<?php
declare(strict_types=1);

namespace App\Core;

use App\Models\User;
use Exception;

class Application
{
    public static string $ROOT_DIR;
    public static Application $app;
    public string $userClass;
    public Router $router;
    public Request $request;
    public Response $response;
    public Controller $controller;
    public Database $db;
    public Session $session;
    public ?DbModel $user;
    public View $view;
    
    public function __construct(string $rootPath, array $config)
    {
        $this->userClass = $config["userClass"] ?? "";
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->db = new Database($config["db"]);
        $this->session = new Session();
        $this->view = new View();
        
        $primaryValue = $this->session->get("user");
        if ($primaryValue) {
            $primaryKey = $this->userClass::primarykey();
            $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
        } else {
            $this->user = null;
        }
    }
    
    public static function isGuest(): bool
    {
        return !self::$app->user;
    }
    
    public static function isAdmin(): bool
    {
        /** @var User|false $user */
        $user = User::findOne(["id" => self::$app->session->get("user")]);
        return $user && (bool)$user->admin;
    }
    
    public function run()
    {
        try {
            echo $this->router->resolve();
        } catch (Exception $e) {
            Application::$app->response->setStatusCode($e->getCode());
            $this->view->setTitle($e->getCode() . " - " . $e->getMessage());
            if (isset($this->controller)) {
                $this->controller->setLayout("main");
            }
            echo $this->view->renderView("_error", [
                "message" => $e->getMessage(),
                "code" => $e->getCode()
            ]);
        }
    }
    
    /**
     * @return Controller
     */
    public function getController(): Controller
    {
        return $this->controller;
    }
    
    /**
     * @param Controller $controller
     */
    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }
    
    public function login(DbModel $user): bool
    {
        $this->user = $user;
        $primaryKey = $user::primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set("user", $primaryValue);
        return true;
    }
    
    public function logout()
    {
        $this->user = null;
        $this->session->remove("user");
    }
}