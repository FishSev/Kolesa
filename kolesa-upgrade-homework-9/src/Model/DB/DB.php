<?php

namespace App\Model\DB;

use \PDO;
use \PDOException;

class DB
{
    protected static $pdo = null;

    public static function getConnection()
    {
        if (self::$pdo == null) {
            self::init();
        }

        try {
            $old_errlevel = error_reporting(0);
            self::$pdo->query("SELECT 1");
        } catch (PDOException $e) {
            echo "Connection failed, reinitializing...\n";
            self::init();
        }
        error_reporting($old_errlevel);

        return self::$pdo;
    }

    protected static $CONFIG_PATH = "..\src\Model\DB\dbConfig.json";

    protected static function init()
    {
        $config = json_decode(file_get_contents(filename:self::$CONFIG_PATH), associative:true) ?? [];


        $dns = "mysql:host=" . $config['servername'] . ";dbname=" . $config['dbname'];
        
        try {
            self::$pdo = new PDO($dns, $config['username'], $config['password']);
            self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}
