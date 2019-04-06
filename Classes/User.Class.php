<?php
    class User {
        
        private $email;
        private $pw; //password
        private $photo; 
        private $profileText; //avatar
 
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
        
        public function getPhoto() {
            return $this->photo;
        }
        public function setPhoto($photo) {
            $this->photo = $photo;
            return $this;
        }

        public function setProfileText($profileText) {
            $this->profileText = $profileText;
        }
    
        public function getProfileText() {
            return $this->profileText;
            return $this;
        }
    }