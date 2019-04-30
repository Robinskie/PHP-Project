<?php
    require_once("bootstrap.php");
    $errorMessage = "";

    if(!empty($_POST)){

        $user = new User();
        $user->setEmail($_POST['email']);
        $user->setPw($_POST['password']);
        
		// gegevens uit velden halen
        $email =  $_POST['email'];
        $password = $_POST['password'];

        if(!$user->filledIn($email)){
            global $errorMessage;
            $errorMessage = "you did not fill in your email";
        } 
        
        else if(!$user->filledIn($password)){
            global $errorMessage;
            $errorMessage = "you did not fill in your password";

        }
        else {      
            
            $result = $user->login();
            if($result != false){
                $_SESSION['userid'] = $result;
                header("Location: index.php");
            } else {
                $errorMessage = "This is not correct, please try again";
            }
        }
    };
    
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./css/login.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,900" rel="stylesheet">
    <title>PROJECT</title>
</head>
<body>
<div class="leftColumn">
    <form action="" method="post" enctype="multipart/form-data" id="form">
        <h2 form__title>Sign in</h2>

        <?php if(!empty($errorMessage)): ?>
            <p id="errorMessage"><?php echo $errorMessage; ?></p>
        <?php endif; ?>

        <label for="email">Email</label>
        <input type="text" id="email" name="email">
        <br>
        <label for="password">Password</label>
        <input type="password" id="password" name="password">
        <br>
        <input type="submit" value="Sign in" id="submitBtn">
    </form>
    <a href="register.php">I don't have an account yet!</a>
</div>
</body>
</html>