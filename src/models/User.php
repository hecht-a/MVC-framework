<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

class User extends Model
{
    public string $tableName = "utilisateurs";
    
    public string $email;
    public string $password;
    public string $firstname;
    public string $lastname;
    public string $tel;
    public bool $admin;
    
    public function register()
    {
    
    }
    
    public function data(): array
    {
        return [
            "email" => $this->email ?? "",
            "firstname" => $this->firstname ?? "",
            "lastname" => $this->lastname ?? "",
            "tel" => $this->tel ?? ""
        ];
    }
    
    public function rules(): array
    {
        return [
            "firstname" => [self::RULE_REQUIRED],
            "lastname" => [self::RULE_REQUIRED],
            "email" => [self::RULE_REQUIRED, self::RULE_EMAIL],
            "tel" => [self::RULE_REQUIRED],
            "password" => [self::RULE_REQUIRED, [self::RULE_MIN, "min" => 8]]
        ];
    }
}