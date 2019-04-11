<?php
    class User {
        
        private $email;
        private $firstName;
        private $lastName;
        private $pw; //password
        private $pwConfirm; // passwordConfirmation
        private $avatar; //avatar
        private $avatarType; //filetype of uploaded avatar
        private $avatarTmpName;
        private $profileText; 
 
        //GETTER & SETTERS in de volgorde dat de variabelen hierboven staan
 
        public function getEmail() {
            return $this->email;
        }
        public function setEmail($email) {
            $this->email = $email;
            return $this;
        }

        public function getFirstName() {
            return $this->firstName;
        }

        public function setFirstName($firstName){
            $this->firstName = $firstName;
            return $this;
        }

        public function getLastName() {
            return $this->lastName;
        }

        public function setLastName($lastName){
            $this->lastName = $lastName;
            return $this;
        }

        public function getPw(){
            return $this->pw;
        }
        
        public function setPw($pw){
           $this->pw = $pw;
           return $this;
        }
        
        public function getPwConfirm(){
            return $this->pwConfirm;
        }
       
        public function setPwConfirm($pwConfirm){
           $this->pwConfirm = $pwConfirm;
           return $this;
        }
        
        public function getAvatar() {
            return $this->avatar;
        }
        public function setAvatar($avatar) {
            $this->avatar = 'images/' . $avatar;
            return $this;
        }

        public function getAvatarType() {
                return $this->avatarType;
        }

        public function setAvatarType($avatarType){
                $this->avatarType = $avatarType;

                return $this;
        }

        
        public function getAvatarTmpName(){
                return $this->avatarTmpName;
        }
 
        public function setAvatarTmpName($avatarTmpName){
                $this->avatarTmpName = $avatarTmpName;

                return $this;
        }

        public function setProfileText($profileText) {
            $this->profileText = $profileText;
        }
    
        public function getProfileText() {
            return $this->profileText;
            return $this;
        }

        public function filledIn($field){
            if(empty($field)){
                return false;
            } else {
                return true;
            }
        }

        public function itemsAreEqual($item1, $item2){
            if($item1 != $item2){
                return false;
            } 
            return true;
        }

        public function checkIfEmailAlreadyExists($email){
            $conn = new PDO("mysql:host=localhost;dbname=project","root","root", null);
            $statement = $conn->prepare("select * from users where email = :email");
            $statement->bindParam(":email",$email);
            $statement->execute();
            $result = $statement->fetch(PDO::FETCH_ASSOC);
 
            if(empty($result)){
                return false; // there's no account with this email
            } else {
                return true; // there's already an account with this email
            }
        }

        public function isPwStrongEnough($pw){
            if(strlen($pw) < 8){
                return false; // password is not strong enough
            }
            return true; // password is strong enough
        }

        public function checkIfFileTypeIsImage($avatarType) {
            if(preg_match('!image!', $_FILES['avatar']['type'])) {
                return true;
            }
            return false;
        }

        public function copyAvatartoImageFolder($avatar) {
            copy($_FILES['avatar']['tmp_name'], $avatar))

        }

        public function register(){
            $options = [
		        'cost' => 12,
		    ];

            $password = password_hash($this->pw,PASSWORD_DEFAULT,$options);

		    try {
			    $conn = new PDO("mysql:host=localhost;dbname=project","root","root", null); // DB CONNECTIE AANPASSEN / ROOT
			    $statement = $conn->prepare("INSERT into users (email,firstName,lastName,password) VALUES (:email,:firstName,:lastName,:password)");
                $statement->bindParam(":email",$this->email);
                $statement->bindParam(":firstName",$this->firstName);
                $statement->bindParam(":lastName",$this->lastName);
			    $statement->bindParam(":password",$password);
			    $result = $statement->execute();
			    return true;

		    } catch(Throwable $t){
			    return false;
            }
        }

    }