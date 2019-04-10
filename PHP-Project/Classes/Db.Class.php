<?php
    abstract class Db {
        private static $conn;

        public static function getInstance() {
            $config = parse_ini_file('./config/config.ini');

            if(self::$conn != null) {
                echo "🚀";
                return self::$conn;
            } else {
                echo "💥";
                self::$conn = new PDO("mysql:host=localhost;dbname=" . $config['db_name'], $config['db_user'], $config['db_password']);
                return self::$conn;
            }
        }
    }