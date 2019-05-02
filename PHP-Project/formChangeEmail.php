<?php
require_once 'bootstrap.php';

redirectIfLoggedOut();

if (!empty($_POST)) {
    echo 'something';
    changeEmail($_POST['oldEmail'], $_POST['newEmail'], $_POST['confirmNewEmail']);
}
function changeEmail($oldEmail, $newEmail, $confirmNewEmail)
{
    $user = new User();
    if (!isset($_SESSION['userid'])) {
        echo 'Sorry, Please login and use this page';
    } else {
        $status = 'OK';
        $msg = '';

        //check if the new email and the confirm email match
        if ($newEmail != $confirmNewEmail) {
            $msg = $msg.'Both email adresses are not matching<BR>';

            $status = 'NOTOK';
        }

        //check if the old email matches the current email in the DB
        //getting the current email from the DB
        $conn = new PDO('mysql:host=localhost;dbname=project', 'root', 'root', null); // DB CONNECTIE AANPASSEN / ROOT
        $statement = $conn->prepare("SELECT Password FROM users WHERE userId='".$_SESSION['userid']."'");
        $result = $statement->execute();

        //check if both emails are the same
        if ($result['email'] != $oldEmail) {
            $msg = $msg.'Your old email  is not matching as per our record.<BR>';

            $status = 'NOTOK';
        }

        //display if something went wrong
        if ($status != 'OK') {
            echo $msg;
        } else { // if all validations are passed.
            //set the new email
            $user->setEmail($newEmail);

            //put the new pw in the DB
        $conn = new PDO('mysql:host=localhost;dbname=project', 'root', 'root', null); // DB CONNECTIE AANPASSEN / ROOT
        $statement = $conn->prepare("UPDATE users SET password=':email' WHERE userId='".$_SESSION['userid']."'");
            $statement->bindParam(':email', $newEmail);
            $result = $statement->execute();

            return true;
        }
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