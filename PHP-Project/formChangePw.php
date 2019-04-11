<?php 
function changepassword ($oldpw, $newpw, $confirmNewPw)
{
        $oldpw = ($_POST['oldpw']);
        $newpw = ($_POST['newpw']);
        $confirmNewPw = ($_POST['confirmNewPw']);

        //check if the new pw and the confirm pw match
        if ($newpw != $confirmNewPw {
            return "your new password and the confirmed password don't match";
        }

        //check if the old pw matches the current pw in the DB
            //getting the current pw from the DB
            $conn = new PDO("mysql:host=localhost;dbname=project","root","root", null); // DB CONNECTIE AANPASSEN / ROOT
            $statement = $conn->prepare("SELECT Password FROM users WHERE UserID = ". $currentUser);
            $result = $statement->execute();
            $oldDbPw = $result['password'];

            $options = [
                'cost' => 12,
            ];
            
            //hashing the old pw the user gave
            $oldpw = password_hash($this->pw,PASSWORD_DEFAULT,$options);

            //checking if the old pw that the user gave and the pw in the DB match
            if ($oldDbPw != $oldpw) {
            return "your old password doesn't match";
            }

        //check if the new pw is strong enough
        $user->isPwStrongEnough($newpw);

        //put the new pw in the DB
        $newpw = password_hash($this->pw,PASSWORD_DEFAULT,$options);

        $conn = new PDO("mysql:host=localhost;dbname=project","root","root", null); // DB CONNECTIE AANPASSEN / ROOT
        $statement = $conn->prepare("UPDATE users SET password=':password'WHERE UserID = :userId");
        $statement->bindParam(":password",$newpw);
        $statement->bindParam(":userId",$currentUser);
        $result = $statement->execute();
        return true;
}   

echo changepassword($_POST['oldpasswd'], $_POST['newpasswd1'], $_POST['newpasswd2']);

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form method="POST" action="changepassword.php">
    <p><input type="password" name="oldPw" id="oldPW" maxlength="30" placeholder="Old Password"></p>
    <p><input type="password" name="newPw" id="newPw" maxlength="30" placeholder="New Password"></p>
    <p><input type="password" name="confirmNewPw" id="ConfirmNewPw"maxlength="30" placeholder="Confirm Password"></p>
    <input type="submit" name="submit" id="submit" value="change password">
    </form>  
</body>
</html>