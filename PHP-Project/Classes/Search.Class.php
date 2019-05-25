<?php
    class Search
    {
        private static $conn;

        public static function searchPhotos($foundPhotos)
        {
            self::$conn = Db::getInstance();
            $statement = self::$conn->prepare('SELECT * FROM photos WHERE description LIKE :foundPhotos ORDER BY uploaddate DESC');
            $statement->bindValue(':foundPhotos', '%'.$foundPhotos.'%');
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function searchProfiles($foundProfiles)
        {
            self::$conn = Db::getInstance();
            $statement = self::$conn->prepare('SELECT * FROM users WHERE firstName LIKE :foundProfiles OR lastName LIKE :foundProfiles');
            $statement->bindValue(':foundProfiles', '%'.$foundProfiles.'%');
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function searchPhotosOnColor($color)
        {
            self::$conn = Db::getInstance();
            $statement = self::$conn->prepare('SELECT photoId as id FROM photoColors WHERE color = :color');
            $statement->bindParam(':color', $color);
            $statement->execute();
            $fetch = $statement->fetchAll(PDO::FETCH_ASSOC);

            return $fetch;
        }

        public static function searchPhotosByTags($tag)
        {
            self::$conn = Db::getInstance();
            $statement = self::$conn->prepare('SELECT id FROM photos WHERE description LIKE :tag');
            $statement->bindValue(':tag', '%'.$tag.'%');
            $statement->execute();
            $fetch = $statement->fetchAll(PDO::FETCH_ASSOC);

            return $fetch;
        }

        public function searchNames()
        {
            $conn = Db::getInstance();
            $statement = $conn->prepare('SELECT name FROM photos UNION SELECT firstName from users UNION SELECT lastName from users');
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function searchPhotosByLocation($lat, $lon, $km)
        {
            self::$conn = Db::getInstance();
            $statement = self::$conn->prepare('SELECT * FROM photos ORDER BY uploadDate DESC');
            $statement->execute();
            $allPhotos = $statement->fetchAll(PDO::FETCH_ASSOC);

            $foundPhotos = array();

            foreach ($allPhotos as $photoRow) {
                if ($photoRow['latitude'] != '') {
                    if (distanceInKmBetweenEarthCoordinates($lat, $lon, $photoRow['latitude'], $photoRow['longitude']) <= $km) {
                        array_push($foundPhotos, $photoRow);
                    }
                }
            }

            return $foundPhotos;
        }
    }
