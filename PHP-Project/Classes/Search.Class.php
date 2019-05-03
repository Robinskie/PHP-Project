<?php
    abstract class Search
    {
        private static $conn;

        // zoekfunctie maken
        public static function searchPhotos($foundPhotos)
        {
            self::$conn = Db::getInstance();
            $statement = self::$conn->prepare("SELECT * FROM photos WHERE description LIKE '%$foundPhotos%'");
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function searchPhotosOnColor($color)
        {
            self::$conn = Db::getInstance();
            $statement = self::$conn->prepare("SELECT photoId FROM photoColors WHERE color = '$color'");
            $statement->execute();
            $fetch = $statement->fetchAll(PDO::FETCH_ASSOC);

            return $fetch;
        }
    }
