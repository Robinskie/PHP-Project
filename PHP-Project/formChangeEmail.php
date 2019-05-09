<?php
require_once 'bootstrap.php';

redirectIfLoggedOut();

if (!empty($_POST)) {
    changeEmail($_POST['oldEmail'], $_POST['newEmail'], $_POST['confirmNewEmail']);
}

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
    <input type="submit" name="submit" id="submit" value="change email">
    </form>  
</body>
</html>