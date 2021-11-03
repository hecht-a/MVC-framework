<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Application;
use App\Core\Controller;
use App\Core\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request): bool|array|string
    {
        $this->setLayout("footer");
        return $this->render("user/login");
    }
    
    public function register(Request $request): bool|array|string
    {
        $user = new User();
        if ($request->isPost()) {
            $user->loadData($request->getBody());
            if ($user->validate() && $user->save()) {
                Application::$app->session->setFlash("success", "Compte créé avec succès");
                Application::$app->response->redirect("/");
                exit;
            }
            return $this->render("user/register", array_merge($user->errors, $user->data()));
        }
        $this->setLayout("footer");
        return $this->render("user/register", array_merge($user->errors, $user->data()));
    }
}