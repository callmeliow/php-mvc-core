<?php

namespace callmeliow\phpmvc\db;

use callmeliow\phpmvc\Application;
use callmeliow\phpmvc\Model;

abstract class DbModel extends Model
{
    abstract public static function tableName(): string;
    abstract public static function attributes(): array;
    abstract public static function primaryKey(): string;

    public function save()
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();

        $params = array_map(fn ($attr) => ":" . $attr, $attributes);
        $sql = "INSERT INTO $tableName (" . implode(',', $attributes) . ") VALUES (" . implode(',', $params) . ")";
        $stmt = self::prepare($sql);

        foreach ($attributes as $attribute) {
            $stmt->bindValue(":$attribute", $this->{$attribute});
        }

        // echo "<pre>";
        // var_dump($stmt, $params, $attributes, $sql);
        // echo "</pre>";
        // exit;

        $stmt->execute();
        return true;
    }

    public static function findOne($where) // ['email' => 'example@gmail.com, 'firstname' => xxx]
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $sql = "SELECT * FROM $tableName WHERE " . implode("AND ", array_map(fn ($attr) => "$attr = :$attr", $attributes));
        $stmt = self::prepare($sql);

        foreach ($where as $attribute => $value) {
            $stmt->bindValue(":$attribute", $value);
        }

        $stmt->execute();

        return $stmt->fetchObject(static::class);
    }

    public static function prepare($sql)
    {
        return Application::$app->db->pdo->prepare($sql);
    }
}
