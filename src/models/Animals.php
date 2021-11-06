<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\DbModel;

class Animals extends DbModel
{
    public int $id;
    public string $type_animal = "";
    
    public static function tableName(): string
    {
        return "animaux";
    }
    
    public function attributes(): array
    {
        return ["type_animal"];
    }
    
    public static function primaryKey(): string
    {
        return "id";
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
}