<?php
    class Like {
        private $postId;
        private $userId;

        //GETTERS en SETTERS
        public function getPostId() {
                return $this->postId;
        }
        public function setPostId($postId) {
                $this->postId = $postId;
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
            $statement = $conn->prepare("insert into likes (post_id, user_id, date_created) values (:postid, :userid, NOW())");
            $statement->bindValue(":postid", $this->getPostId());
            $statement->bindValue(":userid", $this->getUserId());
            return $statement->execute();
        }
    }