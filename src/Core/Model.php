<?php
declare(strict_types=1);

namespace App\Core;

abstract class Model
{
    public const RULE_REQUIRED = "required";
    public const RULE_EMAIL = "email";
    public const RULE_MIN = "min";
    public const RULE_MAX = "max";
    public const RULE_MATCH = "match";
    public const RULE_UNIQUE = "unique";
    public array $errors = [];
    
    /**
     * Permet d'initialiser les données contenues dans un model avant une insertion dans la db
     * @param array $data
     */
    public function loadData(array $data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }
    
    /**
     * Retourne les données contenues dans le model
     * @return array
     */
    abstract public function data(): array;
    
    /**
     * Sert à valider les données contenues dans le model
     * @return bool
     */
    public function validate(): bool
    {
        foreach ($this->rules() as $attribute => $rules) {
            $value = $this->{$attribute};
            foreach ($rules as $rule) {
                $ruleName = $rule;
                if (!is_string($ruleName)) {
                    $ruleName = $rule[0];
                }
                if ($ruleName === self::RULE_REQUIRED && !$value) {
                    $this->addErrorForRule($attribute, self::RULE_REQUIRED);
                }
                if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addErrorForRule($attribute, self::RULE_EMAIL);
                }
                if ($ruleName === self::RULE_MIN && strlen($value) < $rule["min"]) {
                    $this->addErrorForRule($attribute, self::RULE_MIN, $rule);
                }
                if ($ruleName === self::RULE_MAX && strlen($value) > $rule["max"]) {
                    $this->addErrorForRule($attribute, self::RULE_MAX, $rule);
                }
                if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule["match"]}) {
                    $this->addErrorForRule($attribute, self::RULE_MATCH, $rule);
                }
                if ($ruleName === self::RULE_UNIQUE) {
                    $className = $rule["class"];
                    $uniqueAttr = $rule["attribute"] ?? $attribute;
                    $tableName = $className::tableName();
                    $statement = Application::$app->db->prepare("SELECT id FROM $tableName WHERE $uniqueAttr = :attr");
                    $statement->bindValue(":attr", $value);
                    $statement->execute();
                    $record = $statement->fetchObject();
                    if ($record) {
                        $this->addErrorForRule($attribute, self::RULE_UNIQUE, ["field" => $attribute]);
                    }
                }
            }
        }
        return empty($this->errors);
    }
    
    /**
     * Sert à définir les règles de validation des données du model
     * @return array
     */
    abstract public function rules(): array;
    
    /**
     * Permet d'ajouter une erreur pour la règle correspondante
     * @param string $attribute
     * @param string $rule
     * @param array $params
     */
    private function addErrorForRule(string $attribute, string $rule, $params = [])
    {
        $message = $this->errorMessages()[$rule] ?? "";
        foreach ($params as $key => $value) {
            $message = str_replace("{{$key}}", strval($value), $message);
        }
        $this->errors[$attribute . "Error"] = $message;
    }
    
    /**
     * Permet de définir les messages d'erreur en fonction des règles
     * @return string[]
     */
    public function errorMessages(): array
    {
        return [
            self::RULE_REQUIRED => "Ce champ est requis.",
            self::RULE_EMAIL => "Ce champ doit être une adresse email valide.",
            self::RULE_MIN => "La taille minimum de ce champ doit être {min}",
            self::RULE_MAX => "La taille maximale de ce champ doit être {max}",
            self::RULE_MATCH => "Ce champ doit correspondre au champ {match}",
            self::RULE_UNIQUE => "Un enregistrement avec ce {field} existe déjà."
        ];
    }
    
    /**
     * Ajoute une erreur
     * @param string $attribute
     * @param string $message
     */
    public function addError(string $attribute, string $message)
    {
        $this->errors[$attribute . "Error"] = $message;
    }
}