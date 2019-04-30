<?php
    class Photo {
        private $id;
        private $name;
        private $uploader;
        private $uploadDate;
        private $description;
        private $likes;
        private $reports;
        private $input;

        public function getId()
        {
                return $this->id;
        }

        public function setId($id)
        {
                $this->id = $id;
                return $this;
        }

        public function getName()
        {
                return $this->name;
        }

        public function setName($name)
        {
                $this->name = $name;
                return $this;
        }

        public function getUploadDate()
        {
                return $this->uploadDate;
        }

        public function setUploadDate($uploadDate)
        {
                $this->uploadDate = $uploadDate;
                return $this;
        }

        public function getDescription()
        {
                return $this->description;
        }

        public function setDescription($description)
        {
                $this->description = $description;
                return $this;
        }

        public function getUploader()
        {
                return $this->uploader;
        }

        public function setUploader($uploader)
        {
                $this->uploader = $uploader;
                return $this;
        }

        public function checkIfFilledIn($field){
            if(empty($field)){
                return false;
            } else {
                return true;
            }
        }

        public function checkIfFileTypeIsImage($file) {
            if(preg_match('!image!', $file['type'])) {
                return true;
            }
            return false;
        }

        public function cropImage($file, $croppedWidth, $croppedHeight) {
            $originalImage = imagecreatefromstring(file_get_contents($file['tmp_name']));

            $width = imagesx($originalImage);
            $height = imagesy($originalImage);

            $originalAspect = $width / $height;
            $croppedAspect = $croppedWidth / $croppedHeight;

            if ( $originalAspect >= $croppedAspect )
            {
                // If image is wider than thumbnail (in aspect ratio sense)
                $newHeight = $croppedHeight;
                $newWidth = $width / ($height / $croppedHeight);
            }
            else
            {
                // If the thumbnail is wider than the image
                $newWidth = $croppedWidth;
                $newHeight = $height / ($width / $croppedWidth);
            }

            $croppedImage = imagecreatetruecolor( $croppedWidth, $croppedHeight );

            // Resize and crop
            imagecopyresampled($croppedImage,
                            $originalImage,
                            0 - ($newWidth - $croppedWidth) / 2, // Center the image horizontally
                            0 - ($newHeight - $croppedHeight) / 2, // Center the image vertically
                            0, 0,
                            $newWidth, $newHeight,
                            $width, $height);

            return $croppedImage;
        }

        public function getPhotoPath() {
            return "images/photos/" . $this->id . ".png";
        }

        public function getCroppedPhotoPath() {
            return "images/photos/" . $this->id . "_cropped.png";
        }

        // een foto rapporteren

        public function getReportCount(){
                return Db::simpleFetch("SELECT count(*) AS count FROM reports WHERE photo_id=" . $this->id)['count'];
        }

        public function getReportState($userId) {
                return Db::simpleFetch("SELECT count(*) AS count FROM reports WHERE photo_id=" . $this->id . " AND user_id=" . $userId)['count'];
        }

        public function getLikeCount(){
                return Db::simpleFetch("SELECT count(*) AS count FROM likes WHERE photo_id=" . $this->id)['count'];
        }

        public function getLikeState($userId) {
                return Db::simpleFetch("SELECT count(*) AS count FROM likes WHERE photo_id=" . $this->id . " AND user_id=" . $userId)['count'];
        }

        public function setData() {
                $photoRow = Db::simpleFetch("SELECT * FROM photos WHERE id = " . $this->id);
                $this->name = $photoRow['name'];
                $this->uploader = $photoRow['uploader'];
                $this->description = $photoRow['uploader'];
                $this->uploadDate = $photoRow['uploadDate'];
                return $this;
        }

        public function getUploaderObject() {
                $userRow = Db::simpleFetch("SELECT * FROM users WHERE id = " . $this->uploader);
                $user = new User();
                $user->setId($userRow['id']);
                $user->setEmail($userRow['email']);
                $user->setFirstName($userRow['firstName']);
                $user->setLastName($userRow['lastName']);
                $user->setProfileText($userRow['profileText']);
                return $user;
        }
}