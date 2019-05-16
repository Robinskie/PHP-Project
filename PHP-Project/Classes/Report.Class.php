<?php
    class Report
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

        public function reportPicture()
        {
            $conn = Db::getInstance();
            $statement = $conn->prepare('INSERT INTO reports (photo_id, user_id, date_created) VALUES (:photoid, :userid, NOW())');
            $statement->bindValue(':photoid', $this->getPhotoId());
            $statement->bindValue(':userid', $this->getUserId());

            $statement->execute();

            // Photo.Class.php
            $photo = new Photo();
            $photo->setId($this->getPhotoId());
            if ($photo->getReportCount() >= 3) {
                $photo->deletePicture();
            }
        }

        public function takeBack()
        {
            $conn = Db::getInstance();
            $statement = $conn->prepare('DELETE FROM reports WHERE photo_id=:photoid AND user_id=:userid');
            $statement->bindValue(':photoid', $this->getPhotoId());
            $statement->bindValue(':userid', $this->getUserId());

            return $statement->execute();
        }
    }
