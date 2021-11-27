<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Application;
use App\Core\DbModel;

class Consultation extends DbModel
{
    public string $id = "";
    public string $animal = "";
    public string $user = "";
    public string $type_consultation = "";
    public string $problem = "";
    public string $date_prise_rdv = "";
    public string $rdv = "";
    public string $domicile = "0";
    public string $done = "";
    
    public static function tableName(): string
    {
        return "consultations";
    }
    
    public static function primaryKey(): string
    {
        return "id";
    }
    
    public static function findAll(): array
    {
        /** @var Consultation $consultation */
        return array_filter(parent::findAll(), fn($consultation) => Application::$app->session->get("user") === $consultation->user);
    }
    
    public static function isConsultationOwner(string $consultationId): bool
    {
        return self::findOne(["id" => $consultationId])->user === Application::$app->session->get("user");
    }
    
    public function attributes(): array
    {
        return ["animal", "user", "type_consultation", "problem", "rdv", "domicile"];
    }
    
    public function rules(): array
    {
        return [
            "animal" => [self::RULE_REQUIRED],
            "user" => [self::RULE_REQUIRED],
            "type_consultation" => [self::RULE_REQUIRED],
            "problem" => [self::RULE_REQUIRED],
            "rdv" => [self::RULE_REQUIRED],
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
            "rdv" => $this->rdv,
            "domicile" => $this->domicile === "on" ? 1 : 0,
            "id" => $this->id
        ];
    }
    
    public function save(): bool
    {
        return parent::save();
    }
    
    public static function findForAnimal(int $id): array
    {
        $consultations = self::findAll();
        return array_filter($consultations, fn($consultation) => intval($consultation->animal) === $id);
    }
}