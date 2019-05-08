<?php
    class Like
    {
        private $photoId;
        private $userId;

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

        public function save()
        {
            $conn = Db::getInstance();
            $statement = $conn->prepare('insert into likes (photo_id, user_id, date_created) values (:photoid, :userid, NOW())');
            $statement->bindValue(':photoid', $this->getPhotoId());
            $statement->bindValue(':userid', $this->getUserId());

            return $statement->execute();
        }

        public function unSave()
        {
            $conn = Db::getInstance();
            $statement = $conn->prepare('DELETE FROM likes WHERE photo_id=:photoid AND user_id=:userid');
            $statement->bindValue(':photoid', $this->getPhotoId());
            $statement->bindValue(':userid', $this->getUserId());

            return $statement->execute();
        }
    }
