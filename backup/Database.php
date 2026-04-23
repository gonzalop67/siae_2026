<?php

class Database
{
    private $dbcon = null;
    private $stmt = null;
    private static $database = null;

    public function __construct()
    {
        $this->dbcon = new PDO('mysql:dbname=' . Config::get("mysql/db_name") . '; host=' . Config::get("mysql/db_host") . '; charset=' . Config::get("mysql/db_charset"), Config::get("mysql/db_user"), Config::get("mysql/db_pass"));

        $this->dbcon->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $this->dbcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function open_db()
    {
        if (self::$database === null) {
            self::$database = new Database;
        }

        return self::$database;
    }

    public function prepare($query)
    {
        $this->stmt = $this->dbcon->prepare($query);
    }

    public function bindValue($param, $value)
    {
        $type = self::getPDOType($value);
        $this->stmt->bindParam($param, $value, $type);
    }

    public function bindParam($param, $var)
    {
        $type = self::getPDOType($var);
        $this->stmt->bindParam($param, $var, $type);
    }

    private static function getPDOType($value)
    {
        if (is_int($value)) {
            return PDO::PARAM_INT;
        } elseif (is_bool($value)) {
            return PDO::PARAM_BOOL;
        } elseif (is_null($value)) {
            return PDO::PARAM_NULL;
        } else {
            return PDO::PARAM_STR;
        }
    }

    public function execute($arr = null)
    {
        if ($arr === null) {
            return $this->stmt->execute;
        } else {
            return $this->stmt->execute($arr);
        }
    }

    public function fetchColumn()
    {
        return $this->stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function fetchAllAssociative()
    {
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function fetchAssociative()
    {
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function close_db()
    {
        if (isset(self::$database)) {
            self::$database->dbcon = null;
            self::$database->stmt = null;
            self::$database = null;
        }
    }
}
