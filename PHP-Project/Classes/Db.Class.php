<?php
    abstract class Db {
        private static $conn;

        public static function getInstance() {
            $config = parse_ini_file(__DIR__ . '/../config/config.ini');

            if(self::$conn != null) {
                return self::$conn;
            } else {
                self::$conn = new PDO("mysql:host=" . $config['db_host'] . ";dbname=" . $config['db_name'], $config['db_user'], $config['db_password']);
                return self::$conn;
            }
        }

        public static function simpleFetch($query) {
            self::$conn = Db::getInstance();
            $statement = self::$conn->prepare($query);
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        }

        public static function simpleFetchAll($query) {
            self::$conn = Db::getInstance();
            $statement = self::$conn->prepare($query);
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }
    }