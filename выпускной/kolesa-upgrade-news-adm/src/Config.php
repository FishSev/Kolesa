<?php

namespace App;

use Yosymfony\Toml\Toml;

class Config {
    private const CONFIG_PATH = __DIR__ . '/../config/local.toml';
    private static $config = NULL;

    public static function load() {
        if (is_null(self::$config)) {
            self::$config = Toml::ParseFile(self::CONFIG_PATH);
        } 
        return self::$config;
    }
}
