<?php
declare(strict_types=1);

namespace App\Controllers;

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
            if ($user->validate() && $user->register()) {
                return "Success";
            }
            return $this->render("user/register", array_merge($user->errors, $user->data()));
        }
        $this->setLayout("footer");
        return $this->render("user/register", array_merge($user->errors, $user->data()));
    }
}