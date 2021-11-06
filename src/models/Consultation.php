<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\DbModel;
use DateTime;

class Consultation extends DbModel
{
    public int $id;
    public string $animal = "";
    public string $user = "";
    public string $type_consultation = "";
    public string $problem = "";
    public string $date_prise_rdv = "";
    public string $date_rdv = "";
    
    public static function tableName(): string
    {
        return "consultations";
    }
    
    public function attributes(): array
    {
        return ["animal", "user", "type_consultation", "problem", "date_rdv"];
    }
    
    public static function primaryKey(): string
    {
        return "id";
    }
    
    public function rules(): array
    {
        return [
            "animal" => [self::RULE_REQUIRED],
            "user" => [self::RULE_REQUIRED],
            "type_consultation" => [self::RULE_REQUIRED],
            "problem" => [self::RULE_REQUIRED],
            "date_rdv" => [self::RULE_REQUIRED]
        ];
    }
    
    public function data(): array
    {
        return [
            "animal" => $this->animal,
            "user" => $this->user,
            "type_consultation" => $this->type_consultation,
            "problem" => $this->problem,
            "date_prise_rdv" => $this->date_prise_rdv,
            "date_rdv" => (new DateTime($this->date_rdv))->format("Y-m-d H:i")
        ];
    }
    
    public function save(): bool
    {
        return parent::save();
    }
}