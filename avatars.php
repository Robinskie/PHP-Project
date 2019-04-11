<?php
if(isset($_POST['submit'])) {
    $avatar = $_FILES['avatar'];

    $avatarName = $_FILES['avatar']['name'];
    $avatarTmpName = $_FILES['avatar']['tmp_name'];
    $avatarSize = $_FILES['avatar']['size'];
    $avatarError = $_FILES['avatar']['error'];
    $avatarType = $_FILES['avatar']['type'];

    $avatarExt = explode('.', $avatarName);
    $fileActualExt = strolower(end($avatarExt));

    $allowed = array('jpg', 'jpeg', 'png', 'pdf');

    if(in_array($fileActualExt)){
        if ($avatarError === 0) {
            if ($avatarSize < 1000 000) {
                $avatarNameNew = uniqid('', true) . "." . $fileActualExt;
                $fileDestination = 'images/' . $avatarNameNew; 
                move_uploaded_file($avatarTmpName, $fileDestination);
                header("location: index.php");
            } else {
                echo 'Your file was too big';
            }
            
        } else {
            echo 'There was an error uploading your file';
        }
        
    }else {
        echo "you can only upload jpg, jpeg, pdf and png";
    }

    $sql = "select * from user";
    $result = $conn;

    if(mysql_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)){
            $ID = $row['id'];
            $sqlImg = "select * from profileimg where userid=$id";
            $resultImg = mysqli($conn, $sqlImg);
            while ($rowImg = mysqli_fetch_assoc($resultImg)) {
                if ($rowImg['status'] == 0) {
                    echo "<img src='images/profile" . $id . "jpg";
                } else {
                    echo "<img src='images/profiledefault.jpg'>";
                }
            }
        }
    } else {
        echo 'there are no users yet';
    }
}