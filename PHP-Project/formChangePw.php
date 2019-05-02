<?php
require_once 'bootstrap.php';

redirectIfLoggedOut();

if (!empty($_POST)) {
    changePw($_POST['oldPw'], $_POST['newPw'], $_POST['confirmNewPw']);
}

function changePw($oldpw, $newpw, $confirmNewPw) {
        $user = new User();
        //check if user is logged in 
        $status = "OK";
        $msg="";

        //check if the new pw and the confirm pw match
        if ($newpw != $confirmNewPw) {
            $msg=$msg."Both passwords are not matching<BR>";

            $status= "NOTOK";	
        }

        //check if the old pw matches the current pw in the DB
            $userId = $_SESSION["userid"];
            //getting the current pw from the DB
            $conn = Db::getInstance();
            $statement = $conn->prepare("SELECT password FROM users WHERE id= :userid" );
            $statement->bindParam(":userid",$userId);
            $result = $statement->execute();
            $DBresult = $statement->fetch(PDO::FETCH_ASSOC);


            //checking if the old pw that the user gave and the pw in the DB match
            if (!password_verify($oldpw, $DBresult['password'])) {
                $msg=$msg."Your old password  is not matching as per our record.<BR>";
                $status= "NOTOK";
            }

        //check if the new pw is strong enough
        if (!$user->isPwStrongEnough($newpw)) {
            $msg=$msg."Password isn't strong enough<BR>";

            $status= "NOTOK";
        }

        if($status!="OK"){ 
            echo $msg;
            
        }else{ // if all validations are passed.

            //set the new pw
            $user->setPw($newpw);

            $options = [
		        'cost' => 12,
		    ];

            //put the new pw in the DB
            $newpw = password_hash($newpw,PASSWORD_DEFAULT,$options);

            $conn = Db::getInstance(); // DB CONNECTIE AANPASSEN / ROOT
            $statement = $conn->prepare("UPDATE users SET password=:password WHERE id='" . $_SESSION["userid"] . "'");
            $statement->bindParam(":password",$newpw);
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
    <p><input type="password" name="oldPw" id="oldPW"placeholder="Old Password"></p>
    <p><input type="password" name="newPw" id="newPw" placeholder="New Password"></p>
    <p><input type="password" name="confirmNewPw" id="ConfirmNewPw" placeholder="Confirm Password"></p>
    <input type="submit" name="submit" id="submit" value="change password">
    </form>  
 
</body>
</html>