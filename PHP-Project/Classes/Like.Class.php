<?php
    class Like {
        private $postId;
        private $userId;

        //GETTERS en SETTERS
        public function getPhotoId() {
                return $this->photoId;
        }
        public function setPostId($photoId) {
                $this->postId = $photoId;
                return $this;
        }


        public function getUserId() {
                return $this->userId;
        }
        public function setUserId($userId) {
                $this->userId = $userId;
                return $this;
        }

        // functies
        public function save(){

            // @todo: hook in a new function that checks if a user has already liked a post

            $conn = Db::getInstance();
            $statement = $conn->prepare("insert into likes (photo_id, user_id, date_created) values (:photoid, :userid, NOW())");
            $statement->bindValue(":photoid", $this->getPhotoId());
            $statement->bindValue(":userid", $this->getUserId());
            return $statement->execute();
        }
    }