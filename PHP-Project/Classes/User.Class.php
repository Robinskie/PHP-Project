<?php
    class User
    {
        private $id;
        private $email;
        private $firstName;
        private $lastName;
        private $pw;
        private $pwConfirm;
        private $avatar;
        private $avatarType;
        private $avatarTmpName;
        private $profileText;

        private $contactemail;
        private $contactname;
        private $contactmessage;

        public function getId()
        {
            return $this->id;
        }

        public function setId($id)
        {
            $this->id = $id;

            return $this;
        }

        public function getEmail()
        {
            return $this->email;
        }

        public function setEmail($email)
        {
            $this->email = strip_tags($email);

            return $this;
        }

        public function getFirstName()
        {
            return $this->firstName;
        }

        public function setFirstName($firstName)
        {
            $this->firstName = strip_tags($firstName);

            return $this;
        }

        public function getLastName()
        {
            return $this->lastName;
        }

        public function setLastName($lastName)
        {
            $this->lastName = strip_tags($lastName);

            return $this;
        }

        public function getFullName()
        {
            return $this->firstName.' '.$this->lastName;
        }

        public function getPw()
        {
            return $this->pw;
        }

        public function setPw($pw)
        {
            $this->pw = strip_tags($pw);

            return $this;
        }

        public function getPwConfirm()
        {
            return $this->pwConfirm;
        }

        public function setPwConfirm($pwConfirm)
        {
            $this->pwConfirm = strip_tags($pwConfirm);

            return $this;
        }

        public function getAvatar()
        {
            return 'images/avatars/'.$this->avatar;
        }

        public function setAvatar($avatar)
        {
            $this->avatar = $avatar;

            return $this;
        }

        public function getAvatarType()
        {
            return $this->avatarType;
        }

        public function setAvatarType($avatarType)
        {
            $this->avatarType = $avatarType;

            return $this;
        }

        public function getAvatarTmpName()
        {
            return $this->avatarTmpName;
        }

        public function setAvatarTmpName($avatarTmpName)
        {
            $this->avatarTmpName = $avatarTmpName;

            return $this;
        }

        public function setProfileText($profileText)
        {
            $this->profileText = strip_tags($profileText);

            return $this;
        }

        public function getProfileText()
        {
            return $this->profileText;
        }

        public function setData()
        {
            $userRow = Db::simpleFetch('SELECT * FROM users WHERE id = '.$this->id);

            $this->email = $userRow['email'];
            $this->firstName = $userRow['firstName'];
            $this->lastName = $userRow['lastName'];
            $this->pw = $userRow['password'];
            $this->avatar = $userRow['avatar'];
            $this->profileText = $userRow['profileText'];

            return $this;
        }

        public function getUserId($email)
        {
            $conn = Db::getInstance();
            $statement = $conn->prepare('select * from users where email = :email');
            $statement->bindParam(':email', $email);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);

            $this->id = $result['id'];

            return $this->id;
        }

        public function filledIn($field)
        {
            if (empty($field)) {
                return false;
            } else {
                return true;
            }
        }

        public function itemsAreEqual($item1, $item2)
        {
            if ($item1 != $item2) {
                return false;
            }

            return true;
        }

        public function checkIfEmailAlreadyExists($email)
        {
            $conn = Db::getInstance();
            $statement = $conn->prepare('select * from users where email = :email');
            $statement->bindParam(':email', $email);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);

            if (empty($result)) {
                return false;
            } else {
                return true;
            }
        }

        public function isPwStrongEnough($pw)
        {
            if (strlen($pw) < 8) {
                return false;
            }

            return true;
        }

        public function checkIfFileTypeIsImage($avatarType)
        {
            if (preg_match('!image!', $_FILES['avatar']['type'])) {
                return true;
            }

            return false;
        }

        public function copyAvatartoImageFolder($avatar)
        {
            copy($_FILES['avatar']['tmp_name'], $avatar);
        }

        public function register()
        {
            $options = [
                'cost' => 12,
            ];

            $password = password_hash($this->pw, PASSWORD_DEFAULT, $options);

            try {
                $conn = Db::getInstance();
                $statement = $conn->prepare('INSERT into users (email,firstName,lastName, password, avatar, profileText) VALUES (:email,:firstName,:lastName,:password, :avatar, :profileText)');
                $statement->bindParam(':email', $this->email);
                $statement->bindParam(':firstName', $this->firstName);
                $statement->bindParam(':lastName', $this->lastName);
                $statement->bindParam(':password', $password);
                $statement->bindParam(':avatar', $this->avatar);
                $statement->bindParam(':profileText', $this->profileText);
                $result = $statement->execute();

                return $result;
            } catch (Throwable $t) {
                return false;
            }
        }

        public function login()
        {
            $conn = Db::getInstance();
            $statement = $conn->prepare('select * from users where email = :email');
            $statement->bindParam(':email', $this->email);
            $result = $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);

            if (!empty($result)) {
                // password verify uitleggen
                if (password_verify($this->pw, $result['password'])) {
                    return $result['id'];
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }

        public function getFollowState($userId)
        {
            return Db::simpleFetch('SELECT count(*) AS count FROM followers WHERE followedUser = '.$this->id.' AND followingUser = '.$userId)['count'];
        }

        public function getFollowersCount($userId)
        {
            $conn = Db::getInstance();
            $followersCountStatement = $conn->prepare('SELECT * FROM followers WHERE followedUser = :id');
            $followersCountStatement->bindValue(':id', $userId);
            $followersCountStatement->execute();
            $followersCount = $followersCountStatement->rowCount();

            return $followersCount;
        }

        public function getFollowingCount($userId)
        {
            $conn = Db::getInstance();
            $followingCountStatement = $conn->prepare('SELECT * FROM followers WHERE followingUser = :id');
            $followingCountStatement->bindValue(':id', $userId);
            $followingCountStatement->execute();
            $followingCount = $followingCountStatement->rowCount();

            return $followingCount;
        }

        public function getUserPosts($userId)
        {
            $conn = Db::getInstance();
            $userPostsStatement = $conn->prepare('SELECT id, photoFilter FROM photos WHERE uploader = :id ORDER BY uploaddate DESC');
            $userPostsStatement->bindValue(':id', $userId);
            $userPostsStatement->execute();
            $userPosts = $userPostsStatement->fetchAll(PDO::FETCH_ASSOC);

            return $userPosts;
        }

        public function getUserPostsCount($userId)
        {
            $conn = Db::getInstance();
            $userPostsStatement = $conn->prepare('SELECT id FROM photos WHERE uploader = :id ORDER BY uploaddate DESC');
            $userPostsStatement->bindValue(':id', $userId);
            $userPostsStatement->execute();
            $userPostsCount = $userPostsStatement->rowCount();

            return $userPostsCount;
        }

        public function saveEmail($newEmail, $userId)
        {
            $conn = Db::getInstance();
            $statement = $conn->prepare('UPDATE users SET email=:email WHERE id=:userid');
            $statement->bindParam(':email', $newEmail);
            $statement->bindParam(':userid', $userId);
            $result = $statement->execute();

            return $result;
        }

        public function savePw($newPw)
        {
            $conn = Db::getInstance();
            $statement = $conn->prepare("UPDATE users SET password=:password WHERE id='".$_SESSION['userid']."'");
            $statement->bindParam(':password', $newPw);
            $result = $statement->execute();
        }

        public function saveAvatar()
        {
            $conn = Db::getInstance();
            $statement = $conn->prepare("UPDATE users SET avatar=:avatar WHERE id='".$_SESSION['userid']."'");
            $statement->bindParam(':avatar', $this->avatar);
            $result = $statement->execute();
        }

        public function saveProfileText()
        {
            $conn = Db::getInstance();
            $statement = $conn->prepare("UPDATE users SET profiletext=:profileText WHERE id='".$_SESSION['userid']."'");
            $statement->bindParam(':profileText', $this->profileText);
            $result = $statement->execute();
        }
    }
