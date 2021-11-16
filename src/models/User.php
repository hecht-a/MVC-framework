<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\DbModel;

class User extends DbModel
{
    const IS_ADMIN = 1;
    const IS_NOT_ADMIN = 0;
    
    public string $email;
    public string $password = "";
    public string $firstname;
    public string $lastname;
    public string $tel;
    public string $address = "";
    public string $city = "";
    public string $post_code = "";
    public string $id;
    public int $admin = self::IS_NOT_ADMIN;
    
    public static function tableName(): string
    {
        return "users";
    }
    
    public static function primaryKey(): string
    {
        return "id";
    }
    
    public function save(): bool
    {
        $this->admin = self::IS_NOT_ADMIN;
        $this->password = hash("SHA512", $this->password);
        return parent::save();
    }
    
    public function update(): bool
    {
        return parent::update();
    }
    
    public function rules(): array
    {
        return [
            "firstname" => [self::RULE_REQUIRED],
            "lastname" => [self::RULE_REQUIRED],
            "email" => [self::RULE_REQUIRED, self::RULE_EMAIL, [
                self::RULE_UNIQUE, "class" => self::class, "attribute" => "email"
            ]],
            "tel" => [self::RULE_REQUIRED],
            "password" => [self::RULE_REQUIRED, [self::RULE_MIN, "min" => 8]],
            "address" => [self::RULE_REQUIRED],
            "city" => [self::RULE_REQUIRED],
            "post_code" => [self::RULE_REQUIRED]
        ];
    }
    
    public function attributes(): array
    {
        return ["firstname", "lastname", "email", "password", "tel", "admin", "address", "city", "post_code"];
    }
    
    public function data(): array
    {
        return [
            "email" => $this->email ?? "",
            "firstname" => $this->firstname ?? "",
            "lastname" => $this->lastname ?? "",
            "password" => $this->password ?? "",
            "tel" => $this->tel ?? "",
            "address" => $this->address ?? "",
            "city" => $this->city ?? "",
            "post_code" => $this->post_code ?? "",
            "admin" => $this->admin,
            "id" => $this->id ?? "",
        ];
    }
}