<?php
    class Report {
        private $photoId;
        private $userId;

        // getters en setters
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

        public function report(){

            $conn = Db::getInstance();
            $statement = $conn->prepare("insert into reports (photo_id, user_id, date_created) values (:photoid, :userid, NOW())");
            $statement->bindValue(":photoid", $this->getPhotoId());
            $statement->bindValue(":userid", $this->getUserId());
            return $statement->execute();
        }

        public function takeBack(){

            $conn = Db::getInstance();
            $statement = $conn->prepare("DELETE FROM reports WHERE photo_id=:photoid AND user_id=:userid");
            $statement->bindValue(":photoid", $this->getPhotoId());
            $statement->bindValue(":userid", $this->getUserId());
            return $statement->execute();
        }

    }