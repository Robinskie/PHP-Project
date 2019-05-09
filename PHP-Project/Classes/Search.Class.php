<?php
    class Search
    {
        private static $conn;

        public static function searchPhotos($foundPhotos)
        {
            self::$conn = Db::getInstance();
            $statement = self::$conn->prepare("SELECT * FROM photos WHERE description LIKE '%$foundPhotos%' ORDER BY uploaddate DESC");
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function searchPhotosOnColor($color)
        {
            self::$conn = Db::getInstance();
            $statement = self::$conn->prepare("SELECT photoId as id FROM photoColors WHERE color = '$color'");
            $statement->execute();
            $fetch = $statement->fetchAll(PDO::FETCH_ASSOC);

            return $fetch;
        }

        public static function searchPhotosByTags($tag)
        {
            self::$conn = Db::getInstance();
            $statement = self::$conn->prepare("SELECT id FROM photos WHERE description LIKE '%$tag%'");
            $statement->execute();
            $fetch = $statement->fetchAll(PDO::FETCH_ASSOC);

            return $fetch;
        }
    }
