<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Application;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Models\LoginForm;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request, Response $response): bool|array|string
    {
        $loginForm = new LoginForm();
        if ($request->isPost()) {
            $loginForm->loadData($request->getBody());
            if ($loginForm->validate() && $loginForm->login()) {
                $response->redirect("/");
                exit;
            }
        }
        $this->setLayout("footer");
        return $this->render("user/login", array_merge($loginForm->errors, $loginForm->data()));
    }
    
    public function register(Request $request, Response $response): bool|array|string
    {
        $user = new User();
        $this->setLayout("footer");
        if ($request->isPost()) {
            $user->loadData($request->getBody());
            if ($user->validate() && $user->save()) {
                Application::$app->session->setFlash("success", "Compte créé avec succès");
                $response->redirect("/");
                exit;
            }
            return $this->render("user/register", array_merge($user->errors, $user->data()));
        }
        return $this->render("user/register", array_merge($user->errors, $user->data()));
    }
    
    public function logout(Request $request, Response $response)
    {
        Application::$app->logout();
        $response->redirect("/");
    }
}