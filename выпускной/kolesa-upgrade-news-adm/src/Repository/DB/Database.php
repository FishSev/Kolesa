<?php

namespace App\Repository\DB;

use Yosymfony\Toml\Toml;

class Database
{
    private const CONFIG_PATH = __DIR__ . '/../../../config/local.toml';
    private const PATHWORD_PATH = __DIR__ . '/../../../config/DbPassword.txt';
    private static ?Database $database = null;
    private \PDO $pdo;

    public function __construct(array $config)
    {
        if (!isset($config['DB']['host'], $config['DB']['name'], $config['DB']['user'], $config['DB']['password'])) {
            throw new \Exception('Wrong Database config');
        }

        if ($config['DB']['password'] == "") {
            $password = file(self::PATHWORD_PATH); 
            $config['DB']['password'] = $password[0];
        }

        $dsn = sprintf('mysql:host=%s;dbname=%s', $config['DB']['host'], $config['DB']['name']);
        $this->pdo = new \PDO($dsn, $config['DB']['user'], $config['DB']['password']);
    }

    public static function getConnection(): Database
    {
        if (self::$database) {
            return self::$database;
        }

        self::$database = new self(Toml::ParseFile(self::CONFIG_PATH));

        return self::$database;
    }

    public function getPdo(): \PDO
    {
        return $this->pdo;
    }
}
