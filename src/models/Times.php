<?php
declare(strict_types=1);

namespace App\models;


use App\Core\DbModel;
use Exception;

class Times extends DbModel
{
    public string $id;
    public string $day;
    
    public static function tableName(): string
    {
        return "times";
    }
    
    public static function primaryKey(): string
    {
        return "id";
    }
    
    public static function findAllFree(): array
    {
        return array_filter(self::findAll(), fn($time) => !Consultation::findOne(["rdv" => $time->id]));
    }
    
    public function attributes(): array
    {
        return ["day"];
    }
    
    public function rules(): array
    {
        return [];
    }
    
    public function data(): array
    {
        return [
            "id" => $this->id ?? "",
            "day" => $this->day ?? ""
        ];
    }
    
    /**
     * @throws Exception
     */
    public  function __toString(): string
    {
        return strftime("%A %e %B à %H:%M", (new \DateTime($this->day))->getTimestamp());
    }
}