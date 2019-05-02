<?php
require_once 'bootstrap.php';

redirectIfLoggedOut();

if (!empty($_POST)) {
    changeEmail($_POST['oldEmail'], $_POST['newEmail'], $_POST['confirmNewEmail']);
}
function changeEmail($oldEmail, $newEmail, $confirmNewEmail)
{
    $user = new User();
    
    $status = "OK";
    $msg="";

        //check if the new email and the confirm email match
        if ($newEmail != $confirmNewEmail) {
            $msg = $msg.'Both email adresses are not matching<BR>';

            $status = 'NOTOK';
        }

    //check if the old email matches the current email in the DB
        $userId = $_SESSION["userid"];
        //getting the current email from the DB
        $conn = Db::getInstance(); 
        $statement = $conn->prepare("SELECT email FROM users WHERE id=:userid");
        $statement->bindParam(":userid",$userId);      
        $result = $statement->execute();
        $DBresult = $statement->fetch(PDO::FETCH_ASSOC);

        var_dump($DBresult['email']);

        //check if both emails are the same
        if ($DBresult['email'] != $oldEmail) {
            $msg=$msg."Your old email  is not matching as per our record.<BR>";
            $status= "NOTOK";
        }

        //display if something went wrong
        if ($status != 'OK') {
            echo $msg;
        } else { // if all validations are passed.
            //set the new email
            $user->setEmail($newEmail);


        //put the new pw in the DB
        $conn = Db::getInstance();
        $statement = $conn->prepare("UPDATE users SET email=:email WHERE id=:userid");
        $statement->bindParam(":email",$newEmail);
        $statement->bindParam(":userid",$userId);  
        $result = $statement->execute();
        return true;
        }

    };

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