<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Application;
use App\Core\Model;

class LoginForm extends Model
{
    public string $email = "";
    public string $password = "";
    
    public function rules(): array
    {
        return [
            "email" => [self::RULE_REQUIRED, self::RULE_EMAIL],
            "password" => [self::RULE_REQUIRED]
        ];
    }
    
    public function login(): bool
    {
        $user = User::findOne(["email" => $this->email]);
        if (!$user) {
            $this->addError("email", "Aucun utilisateur existe avec cet email");
            return false;
        }
        if (hash("SHA512", $this->password) !== $user->password) {
            $this->addError("password", "Le mot de passe est incorrect");
            return false;
        }
        return Application::$app->login($user);
    }
    
    public function data(): array
    {
        return [
            "email" => $this->email ?? "",
        ];
    }
}