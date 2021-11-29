<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOStatement;

abstract class DbModel extends Model
{
    /**
     * Permet de spécifier la clé primaire de la table
     * @return string
     */
    abstract public static function primaryKey(): string;
    
    /**
     * Permet de récupérer une ligne de la db en fonction de la condition `$where`
     * @param $where
     * @return DbModel|bool
     */
    public static function findOne($where): DbModel|bool
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
    
    /**
     * Permet de spécifier le nom de la table
     * @return string
     */
    abstract public static function tableName(): string;
    
    /**
     * Prépare une requête SQL
     * @param $sql
     * @return bool|PDOStatement
     */
    public static function prepare($sql): bool|PDOStatement
    {
        return Application::$app->db->pdo->prepare($sql);
    }
    
    /**
     * Permet de récupérer toutes les lignes d'une table
     * @return DbModel[]
     */
    public static function findAll(): array
    {
        $tableName = static::tableName();
        $statement = self::prepare("SELECT * FROM $tableName");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_CLASS, static::class);
    }
    
    /**
     * Permet de supprimer une ligne de la db en fonction de la condition `$where`
     * @param $where
     */
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
    
    /**
     * Permet d'effectuer une insertion dans la db
     * @return bool
     */
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
    
    /**
     * Sert à spécifier les attributs à insérer dans la db
     * @return array
     */
    abstract public function attributes(): array;
    
    /**
     * Permet de mettre à jour une ligne de la db
     * @return bool
     */
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