<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\DbModel;

class TypeConsultation extends DbModel
{
    public int $id;
    public string $consultation = "";
    
    public static function tableName(): string
    {
        return "type_consultation";
    }
    
    public function attributes(): array
    {
        return ["id", "consultation"];
    }
    
    public static function primaryKey(): string
    {
        return "id";
    }
    
    public function rules(): array
    {
        return [
            "consultation" => [self::RULE_REQUIRED],
        ];
    }
    
    public function data(): array
    {
        return [
            "consultation" => $this->consultation
        ];
    }
}