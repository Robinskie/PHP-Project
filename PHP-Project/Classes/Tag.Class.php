<?php
    class Tag
    {
        private $tagName;
        private $userId;

        public function getTagName()
        {
            return $this->tagName;
        }

        public function setTagName($tagName)
        {
            $this->tagName = $tagName;

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

        public function getFollowState($userId)
        {
            $tagFollow = Db::simpleFetch('SELECT count(*) AS count FROM tags WHERE tagName = "'.$this->tagName.'" AND followingUser = '.$userId)['count'];

            return $tagFollow;
        }

        public function save()
        {
            $conn = Db::getInstance();
            $statement = $conn->prepare('insert into tags (tagName, followingUser) values (:thisTag, :userid)');
            $statement->bindValue(':thisTag', $this->tagName);
            $statement->bindValue(':userid', $this->getUserId());

            return $statement->execute();
        }

        public function unSave()
        {
            $conn = Db::getInstance();
            $statement = $conn->prepare('DELETE FROM tags WHERE tagName = :thisTag AND followingUser = :userid');
            $statement->bindValue(':thisTag', $this->getTagName());
            $statement->bindValue(':userid', $this->getUserId());

            return $statement->execute();
        }
    }
