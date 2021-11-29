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
        $this->controller = new Controller();
        
        $primaryValue = $this->session->get("user");
        if ($primaryValue) {
            $primaryKey = $this->userClass::primarykey();
            $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
        } else {
            $this->user = null;
        }
    }
    
    /**
     * false si l'utilisateur est connecté, true sinon
     * @return bool
     */
    public static function isGuest(): bool
    {
        return !self::$app->user;
    }
    
    /**
     * true si l'utilisateur est admin, false sinon
     * @return bool
     */
    public static function isAdmin(): bool
    {
        /** @var User|false $user */
        $user = User::findOne(["id" => self::$app->session->get("user")]);
        return $user && (bool)$user->admin;
    }
    
    /**
     * Lance le framework
     */
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
     * Retourne le controller utilisé par la page visitée
     * @return Controller
     */
    public function getController(): Controller
    {
        return $this->controller;
    }
    
    /**
     * Définit le controller de la page voulue
     * @param Controller $controller
     */
    public function setController(Controller $controller): void
    {
        $this->controller = $controller;
    }
    
    /**
     * Connecte un utilisateur
     * @param DbModel $user
     * @return bool
     */
    public function login(DbModel $user): bool
    {
        $this->user = $user;
        $primaryKey = $user::primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set("user", $primaryValue);
        return true;
    }
    
    /**
     * Déconnecte l'utilisateur
     */
    public function logout()
    {
        $this->user = null;
        $this->session->remove("user");
    }
}