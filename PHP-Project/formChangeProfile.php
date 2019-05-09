<?php
require_once 'bootstrap.php';

redirectIfLoggedOut();

if(!empty($_POST['submit'])) {

    //welke form word gebruikt?
    switch($_POST['submit']) {

        //email veranderen
        case 'Change email': 
        
        function changeEmail($oldEmail, $newEmail, $confirmNewEmail)    {
            $user = new User();
            $user->setId($_SESSION['userid']);
            $user->setData();
        
            $status = 'OK';
            $msg = '';
            
            if ($newEmail != $confirmNewEmail) {
                $msg = $msg.'Both email adresses are not matching<BR>';
                $status = 'NOTOK';
            }
        
            if ($user->getEmail() != $oldEmail) {
                $msg = $msg.'Your old email  is not matching as per our record.<BR>';
                $status = 'NOTOK';
            }
        
            if ($status != 'OK') {
                echo $msg;
            } else {
                $user->setEmail($newEmail);
                $user->saveEmail($newEmail, $user->getId());
        
                echo "Your email is changed";
                return true;
            }
        }

        if (!empty($_POST)) {
            changeEmail($_POST['oldEmail'], $_POST['newEmail'], $_POST['confirmNewEmail']);
        }
            break;

        //email veranderen
        case 'Change password':
        
        function changePw($oldPw, $newPw, $confirmNewPw)
        {
            $user = new User();
            $user->setId($_SESSION['userid']);
            $user->setData();
        
            $status = 'OK';
            $msg = '';
        
            if ($newPw != $confirmNewPw) {
                $msg = $msg.'Both passwords are not matching<BR>';
                $status = 'NOTOK';
            }
        
            if (!password_verify($oldPw, $user->getPw()) ) {
                $msg = $msg.'Your old password  is not matching as per our record.<BR>';
                $status = 'NOTOK';
            }
        
            if (!$user->isPwStrongEnough($newPw)) {
                $msg = $msg."Password isn't strong enough<BR>";
                $status = 'NOTOK';
            }
        
            if ($status != 'OK') {
                echo $msg;
            } else {
                $user->setPw($newPw);
        
                $options = [
                        'cost' => 12,
                    ];
        
                $newPw = password_hash($newPw, PASSWORD_DEFAULT, $options);
                $user->savePw($newPw);
                
                echo "Your password is changed";
                return true;
                
            }
        }

        if (!empty($_POST)) {
            changePw($_POST['oldPw'], $_POST['newPw'], $_POST['confirmNewPw']);
        }
            break;

        //profieltekst aanpassen
        case 'Change profiletext':
        function changeProfileText($newProfileText)
        {
            $user = new User();
            $user->setId($_SESSION['userid']);
            $user->setData();
            $user->setProfileText($newProfileText);
            $user->saveProfileText();
                
            echo "Your profiletext is changed";
            return true;
  
        }

        if (!empty($_POST)) {
            changeProfileText($_POST['profileText']);
        }

        break;
        //avatar veranderen
        case 'Change avatar':

            function changeAvatar($newAvatar) {
                $user = new User();
                $user->setId($_SESSION['userid']);
                $user->setData();

                $status = 'OK';
                $msg = '';

                if (!$user->checkIfFileTypeIsImage($newAvatar['type'])) {
                    $msg = $msg.'Your file is not an image';
                    $status = 'NOTOK';
                }

                if ($status != 'OK') {
                    echo $msg;
                } else {
                    $user->setAvatar($newAvatar['name']);
                    $user->setAvatarType($newAvatar['type']);
                    $user->setAvatarTmpName($newAvatar['tmp_name']);
                    $user->copyAvatartoImageFolder($user->getAvatar());
                    $user->saveAvatar($user->getAvatar());

                    echo "Your avatar is changed";
                    return true;
                }
            }
        
        if (!empty($_FILES)) {
            changeAvatar($_FILES['avatar']);
        }

        break;
        //end of switch/case
    }
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form method="POST" action="">
        <p><input type="email" name="oldEmail" id="oldEmail" placeholder="Old Email"></p>
        <p><input type="email" name="newEmail" id="newEmail" placeholder="New Email"></p>
        <p><input type="email" name="confirmNewEmail" id="ConfirmNewEmail" placeholder="Confirm Email"></p>
        <input type="submit" name="submit" id="submit" value="Change email">
    </form>  

    <form method="POST" action="">
        <p><input type="password" name="oldPw" id="oldPW"placeholder="Old Password"></p>
        <p><input type="password" name="newPw" id="newPw" placeholder="New Password"></p>
        <p><input type="password" name="confirmNewPw" id="ConfirmNewPw" placeholder="Confirm Password"></p>
        <input type="submit" name="submit" id="submit" value="Change password">
    </form>  

    <form method="POST"action="" enctype="multipart/form-data">
        <label for="avatar">Choose an new avatar</label> 
        <br>
        <input type="file" name="avatar" accept="image/*" id="avatar"/>
        <br>
        <input type="submit" name="submit" id="submit" value="Change avatar">
    </form>

    <form method="POST" action="">
        <label for="profileText">Write your profile text here</label>
        <input type="text" name="profileText" id="profileText">
        <input type="submit" name="submit" id="submit" value="Change profiletext">
    </form>

</body>
</html>