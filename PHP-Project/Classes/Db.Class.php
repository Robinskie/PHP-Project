<?php
    abstract class Db
    {
        private static $conn;

        public static function getInstance()
        {
            $config = parse_ini_file(__DIR__.'/../config/config.ini');

            if (self::$conn != null) {
                return self::$conn;
            } else {
                try {
                    self::$conn = new PDO('mysql:host='.$config['db_host'].';port='.$config['db_port'].';dbname='.$config['db_name'], $config['db_user'], $config['db_password']);
                } catch (PDOException $e) {
                    var_dump($e);
                }

                return self::$conn;
            }
        }

        public static function simpleFetch($query)
        {
            self::$conn = Db::getInstance();
            $statement = self::$conn->prepare($query);
            $statement->execute();

            return $statement->fetch(PDO::FETCH_ASSOC);
        }

        public static function simpleFetchAll($query)
        {
            self::$conn = Db::getInstance();
            $statement = self::$conn->prepare($query);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);
        }

        public static function getUserFeed($userId, $from, $to)
        {
            $postLimit = $to - $from;
            $conn = Db::getInstance();
            $statement = $conn->prepare(
                'SELECT *, photos.id AS pId, users.id AS uId FROM photos
                LEFT JOIN users ON photos.uploader = users.id
                RIGHT JOIN followers ON followers.followedUser = photos.uploader
                WHERE followers.followingUser = :currentUser
                ORDER BY uploadDate DESC
                LIMIT '.$postLimit.' OFFSET '.$from);
            $statement->bindParam(':currentUser', $userId);
            $result = $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);

            /*
            // FEED MET POSTS VAN FOLLOWED USERS EN TAGS
            $conn = Db::getInstance();
            $statement = $conn->prepare('SELECT tagName FROM tags WHERE followingUser = '.$userId);
            $statement->execute();
            $tags = $statement->fetchAll(PDO::FETCH_ASSOC);
            //var_dump($tags);

            $queryStart = 'SELECT *, photos.id AS pId, users.id AS uId FROM photos
            LEFT JOIN users ON photos.uploader = users.id
            RIGHT JOIN followers ON followers.followedUser = photos.uploader
            WHERE followers.followingUser = :currentUser ';

            //var_dump($queryStart);

            $count = 0;

            foreach ($tags as $tag) {
                $tagName = $tag['tagName'];
                $queryName = 'query'.$count;
                //var_dump($tagName);
                $$queryName = 'UNION
                SELECT * FROM photos WHERE description LIKE %'.$tagName.'% ';
                ++$count;
            }

            //var_dump($query2);

            $queryEnd = 'ORDER BY uploadDate DESC
            LIMIT '.$postLimit.' OFFSET '.$from;

            //var_dump($queryEnd);

            $finalQuery = $queryStart.$queryEnd;

            var_dump($finalQuery);

            $conn = Db::getInstance();
            $statement = $conn->prepare($finalQuery);
            $statement->bindParam(':currentUser', $userId);
            $statement->execute();

            return $statement->fetchAll(PDO::FETCH_ASSOC);

            */
        }

        public static function getRandomOtherUser($userId)
        {
            $conn = Db::getInstance();
            $randomUserStatement = $conn->prepare("SELECT id FROM users WHERE NOT id = $userId ORDER BY RAND() LIMIT 1");
            $randomUserStatement->execute();
            $fetch = $randomUserStatement->fetch(PDO::FETCH_ASSOC);

            $randomUser = new User();
            $randomUser->setId($fetch['id']);
            $randomUser->setData();

            return $randomUser;
        }
    }
