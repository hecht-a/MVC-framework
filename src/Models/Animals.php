<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\DbModel;

class Animals extends DbModel
{
    public int $id;
    public string $name;
    public int $height;
    public int $weight;
    public string $type = "";
    public int $age;
    public int $owner;
    
    public static function tableName(): string
    {
        return "animals";
    }
    
    public static function primaryKey(): string
    {
        return "id";
    }
    
    public function attributes(): array
    {
        return ["type_animal"];
    }
    
    public function rules(): array
    {
        return [
            "type_animal" => [self::RULE_REQUIRED]
        ];
    }
    
    public function data(): array
    {
        return [
            "type_animal" => $this->type_animal
        ];
    }
    
    public static function findAllForUser(int $id): array
    {
        $animals = self::findAll();
        return array_filter($animals, fn($animal) => $animal->owner === $id);
    }
}