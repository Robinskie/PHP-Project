<?php
require_once 'bootstrap.php';

redirectIfLoggedOut();

if (!empty($_POST)) {
    changePw($_POST['oldPw'], $_POST['newPw'], $_POST['confirmNewPw']);
}

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
    <p><input type="password" name="oldPw" id="oldPW"placeholder="Old Password"></p>
    <p><input type="password" name="newPw" id="newPw" placeholder="New Password"></p>
    <p><input type="password" name="confirmNewPw" id="ConfirmNewPw" placeholder="Confirm Password"></p>
    <input type="submit" name="submit" id="submit" value="Change password">
    </form>  
 
</body>
</html>