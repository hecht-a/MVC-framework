<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOStatement;

abstract class DbModel extends Model
{
    abstract public static function primaryKey(): string;
    
    public static function findOne($where)
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $params = implode(" AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $statement = self::prepare("SELECT * FROM $tableName WHERE $params");
        foreach ($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }
        $statement->execute();
        return $statement->fetchObject(static::class);
    }
    
    public static function findAll(): array
    {
        $tableName = static::tableName();
        $statement = self::prepare("SELECT * FROM $tableName");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_CLASS, static::class);
    }
    
    public static function delete($where)
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $params = implode(" AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $statement = self::prepare("DELETE FROM $tableName WHERE $params");
        foreach ($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }
        $statement->execute();
    }
    
    public function save(): bool
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(fn($attr) => ":$attr", $attributes);
        $statement = self::prepare("INSERT INTO $tableName (" . implode(",", $attributes) . ") VALUES (" . implode(",", $params) . ")");
        foreach ($attributes as $attribute) {
            $statement->bindValue(":$attribute", $this->data()[$attribute]);
        }
        $statement->execute();
        return true;
    }
    
    abstract public static function tableName(): string;
    
    abstract public function attributes(): array;
    
    public static function prepare($sql): bool|PDOStatement
    {
        return Application::$app->db->pdo->prepare($sql);
    }
    
    public function update(): bool
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(fn($attr) => "$attr = :$attr", $attributes);
        $statement = self::prepare("UPDATE $tableName SET " . implode(", ", $params) . " WHERE id = " . $this->data()["id"]);
        foreach ($attributes as $attribute) {
            $statement->bindValue(":$attribute", $this->data()[$attribute]);
        }
        $statement->execute();
        return true;
    }
}