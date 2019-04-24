<?php
    class Photo {
        private $id;
        private $name;
        private $uploader;
        private $uploadDate;
        private $description;
        private $tags;
        private $date;
        private $likes;

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

        public function getTags()
        {
                return $this->tags;
        }

        public function setTags($tags)
        {
                $this->tags = $tags;
                return $this;
        }

        public function getDate()
        {
                return $this->date;
        }

        public function setDate($date)
        {
                $this->date = $date;
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

        public function getLikes(){
                $conn = Db::getInstance();
                $statement = $conn->prepare("select count(*) as count from likes where photo_id = :photoid");
                $statement->bindValue(":photoid", $this->id);
                $statement->execute();
                $result = $statement->fetch(PDO::FETCH_ASSOC);
                return $result['count'];
            }
    }