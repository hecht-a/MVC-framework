<?php
declare(strict_types=1);

namespace App\Core;


use JetBrains\PhpStorm\ArrayShape;

abstract class Model
{
    public const RULE_REQUIRED = "required";
    public const RULE_EMAIL = "email";
    public const RULE_MIN = "min";
    public const RULE_MAX = "max";
    public const RULE_MATCH = "match";
    public const RULE_UNIQUE = "unique";
    
    public function loadData($data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }
    
    public array $errors = [];
    
    abstract public function rules(): array;
    
    abstract public function data(): array;
    
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
                    $this->addError($attribute, self::RULE_REQUIRED);
                }
                if ($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($attribute, self::RULE_EMAIL);
                }
                if ($ruleName === self::RULE_MIN && strlen($value) < $rule["min"]) {
                    $this->addError($attribute, self::RULE_MIN, $rule);
                }
                if ($ruleName === self::RULE_MAX && strlen($value) > $rule["max"]) {
                    $this->addError($attribute, self::RULE_MAX, $rule);
                }
                if ($ruleName === self::RULE_MATCH && $value !== $this->{$rule["match"]}) {
                    $this->addError($attribute, self::RULE_MATCH, $rule);
                }
            }
        }
        
        return empty($this->errors);
    }
    
    public function addError(string $attribute, string $rule, $params = [])
    {
        $message = $this->errorMessages()[$rule] ?? "";
        foreach ($params as $key => $value) {
            $message = str_replace("{{$key}}", strval($value), $message);
        }
        $this->errors[$attribute . "Error"] = $message;
    }
    
    public function errorMessages(): array
    {
        return [
            self::RULE_REQUIRED => "Ce champ est requis.",
            self::RULE_EMAIL => "Ce champ doit être une adresse email valide.",
            self::RULE_MIN => "La taille minimum de ce champ doit être {min}",
            self::RULE_MAX => "La taille maximale de ce champ doit être {max}",
            self::RULE_MATCH => "Ce champ doit correspondre au champ {match}"
        ];
    }
    
    public function hasError($attribute)
    {
        return $this->errors[$attribute] ?? false;
    }
    
    public function getFirstError($attribute)
    {
        return $this->errors[$attribute][0] ?? false;
    }
}