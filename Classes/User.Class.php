<?php
    class User {
        
        private $email;
        private $pw; //password
        private $pwConfirm; // passwordConfirmation
        private $avatar; //avatar
        private $profileText; 
 
        //GETTER & SETTERS in de volgorde dat de variabelen hierboven staan
 
        public function getEmail() {
            return $this->email;
        }
        public function setEmail($email) {
            $this->email = $email;
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
            $this->avatar = $avatar;
            return $this;
        }

        public function setProfileText($profileText) {
            $this->profileText = $profileText;
        }
    
        public function getProfileText() {
            return $this->profileText;
            return $this;
        }

        public function register(){
            $options = [
		        'cost' => 12,
		    ];
            
            $password = password_hash($this->pw,PASSWORD_DEFAULT,$options);

		    try {
			    $conn = new PDO("mysql:host=localhost;dbname=project","root","root", null); // DB CONNECTIE AANPASSEN / ROOT
			    $statement = $conn->prepare("INSERT into users (email,password, avatar) VALUES (:email,:password, :avatar)");
			    $statement->bindParam(":email",$this->email);
                $statement->bindParam(":password",$password);
                $statement->bindParam(":avatar",$avatar);
			    $result = $statement->execute();
			    return $result;

		    } catch(Throwable $t){
			    return false;
            }
        }
    }