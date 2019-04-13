<?php 
if (!empty($_POST)) {
    changePw($_POST['oldpw'], $_POST['newpw'], $_POST['confirmNewPw']);
    changeEmail($_POST['oldEmail'], $_POST['newEmail'], $_POST['confirmNewEmail']);
    echo $newpw;
}

function changePw ($oldpw, $newpw, $confirmNewPw) {

        //check if user is logged in 
        if(!isset($_SESSION['userid'])){
            echo "Sorry, Please login and use this page";
        }
        $status = "OK";
        $msg="";

        //check if the new pw and the confirm pw match
        if ($newpw != $confirmNewPw) {
            $msg=$msg."Both passwords are not matching<BR>";

            $status= "NOTOK";}	
        }

        //check if the old pw matches the current pw in the DB
            //getting the current pw from the DB
            $conn = new PDO("mysql:host=localhost;dbname=project","root","root", null); // DB CONNECTIE AANPASSEN / ROOT
            $statement = $conn->prepare("SELECT Password FROM users WHERE userId='" . $_SESSION["userId"] . "'");
            $result = $statement->execute();

            //hashing the old pw the user gave
            $options = [
                'cost' => 12,
            ];
            
            $oldpw = password_hash($oldpw,PASSWORD_DEFAULT,$options);

            //checking if the old pw that the user gave and the pw in the DB match
            if ($result['password'] != $oldpw) {
            
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

            //put the new pw in the DB
            $newpw = password_hash($this->pw,PASSWORD_DEFAULT,$options);

            $conn = new PDO("mysql:host=localhost;dbname=project","root","root", null); // DB CONNECTIE AANPASSEN / ROOT
            $statement = $conn->prepare("UPDATE users SET password=':password' WHERE userId='" . $_SESSION["userId"] . "'");
            $statement->bindParam(":password",$newpw);
            $result = $statement->execute();
            return true;
        }
};   


function changeEmail ($oldEmail, $newEmail, $confirmNewEmail) {

    if(!isset($_SESSION['userid'])){
        echo "Sorry, Please login and use this page";
    }
    $status = "OK";
    $msg="";

    //check if the new email and the confirm email match
    if ($newEmail != $confirmNewEmail) {
        $msg=$msg."Both email adresses are not matching<BR>";

        $status= "NOTOK";}	
    }

    //check if the old email matches the current email in the DB
        //getting the current email from the DB
        $conn = new PDO("mysql:host=localhost;dbname=project","root","root", null); // DB CONNECTIE AANPASSEN / ROOT
        $statement = $conn->prepare("SELECT Password FROM users WHERE userId='" . $_SESSION["userId"] . "'");
        $result = $statement->execute();

        //check if both emails are the same
        if ($result['email'] != $oldEmail) {
            
            $msg=$msg."Your old email  is not matching as per our record.<BR>";

            $status= "NOTOK";
        }

    //display if something went wrong
    if($status!="OK"){ 
        echo $msg;
            
    }else{ // if all validations are passed.

        //set the new email
        $user->setEmail($newEmail);

        //put the new pw in the DB
        $conn = new PDO("mysql:host=localhost;dbname=project","root","root", null); // DB CONNECTIE AANPASSEN / ROOT
        $statement = $conn->prepare("UPDATE users SET password=':email' WHERE userId='" . $_SESSION["userId"] . "'");
        $statement->bindParam(":email",$newEmail);
        $result = $statement->execute();
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
    <input type="submit" name="submit" id="submit" value="change password">
    </form>  

    <form method="POST" action="">
    <p><input type="email" name="oldEmail" id="oldEmail" placeholder="Old Email"></p>
    <p><input type="email" name="newEmail" id="newEmail" placeholder="New Email"></p>
    <p><input type="email" name="confirmNewEmail" id="ConfirmNewEmail" placeholder="Confirm Email"></p>
    <input type="submit" name="submit" id="submit" value="change email">
    </form>  
</body>
</html>