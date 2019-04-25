<?php
    class Comment {
        private $photoId;
        private $userId;
        private $text;
        private $date;

        //GETTERS en SETTERS
        public function getPhotoId() {
                return $this->photoId;
        }
        public function setPhotoId($photoId) {
                $this->photoId = $photoId;
                return $this;
        }

        public function getUserId() {
            return $this->userId;
        }
        public function setUserId($userId) {
            $this->userId = $userId;
            return $this;
        }

        public function getText() {
            return $this->text;
        }
        public function setText($text) {
            $this->text = $text;
            return $this;
        }

        public function getDate() {
            return $this->date;
        }
        public function setDate($date) {
            $this->date = $date;
            return $this;
        }

        // functies
        public function save(){
            $conn = Db::getInstance();
            $statement = $conn->prepare("INSERT INTO comments (photoId, userId, text, date) VALUES (:photoId, :userId, :commentText, NOW())");
            $statement->bindValue(":photoId", $this->getPhotoId());
            $statement->bindValue(":userId", $this->getUserId());
            $statement->bindValue(":commentText", $this->getText());
            return $statement->execute();
        }
    }