<?php
    class Follow
    {
        private $thisUserId;
        private $userId;

        //GETTERS en SETTERS
        public function getThisUserId()
        {
            return $this->thisUserId;
        }

        public function setThisUserId($thisUserId)
        {
            $this->thisUserId = $thisUserId;

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

        // functies
        public function save()
        {
            $conn = Db::getInstance();
            $statement = $conn->prepare('insert into followers (followedUser, followingUser) values (:thisUserId, :userid)');
            $statement->bindValue(':thisUserId', $this->getThisUserId());
            $statement->bindValue(':userid', $this->getUserId());

            return $statement->execute();
        }

        public function unSave()
        {
            $conn = Db::getInstance();
            $statement = $conn->prepare('DELETE FROM followers WHERE followedUser = :thisUserId AND followingUser = :userid');
            $statement->bindValue(':thisUserId', $this->getThisUserId());
            $statement->bindValue(':userid', $this->getUserId());

            return $statement->execute();
        }
    }
