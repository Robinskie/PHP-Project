<?php
    class Comment
    {
        private $id;
        private $photoId;
        private $userId;
        private $text;
        private $date;

        public function getId()
        {
            return $this->id;
        }

        public function setId($id)
        {
            $this->id = $id;

            return $id;
        }

        public function getPhotoId()
        {
            return $this->photoId;
        }

        public function setPhotoId($photoId)
        {
            $this->photoId = $photoId;

            return $this;
        }

        public function getUserId()
        {
            return $this->userId;
        }

        public function setUserId($userId)
        {
            $this->userId = $userId;

            return $this;
        }

        public function getText()
        {
            return $this->text;
        }

        public function setText($text)
        {
            $this->text = $text;

            return $this;
        }

        public function getDate()
        {
            return $this->date;
        }

        public function setDate($date)
        {
            $this->date = $date;

            return $this;
        }

        public function setData()
        {
            $commentRow = Db::simpleFetch('SELECT * FROM comments WHERE id = '.$this->id);
            $this->photoId = $commentRow['photoId'];
            $this->userId = $commentRow['userId'];
            $this->text = $commentRow['text'];
            $this->date = $commentRow['date'];

            return $this;
        }

        public function getCommenterObject()
        {
            $commentUser = new User();
            $commentUser->setId($this->userId);
            $commentUser->setData();

            return $commentUser;
        }

        public function save()
        {
            $conn = Db::getInstance();
            $statement = $conn->prepare('INSERT INTO comments (photoId, userId, text, date) VALUES (:photoId, :userId, :commentText, NOW())');
            $statement->bindValue(':photoId', $this->getPhotoId());
            $statement->bindValue(':userId', $this->getUserId());
            $statement->bindValue(':commentText', $this->getText());

            return $statement->execute();
        }
    }
