
<?php

session_start();
require_once("classes/User.class.php");

if( !empty($_POST))	{
    // gegevens uit velden halen
    $email =  $_POST['Email'];
    $password = $_POST['Password'];

    // databank connectie
    $conn = new PDO("mysql:host=localhost;dbname=project","root","root", null); 
    $statement = $conn->prepare("select * from users where email = :email "); 
    $statement->bindParam(":email", $email); 
    $result = $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    // functie gebruiken die we aangemaakt hebben 
    if ( password_verify($password, $user['password']) ){

    session_start();        
    $_SESSION['userid'] = $user['id'];
    header('Location:index.php');
    }

    else {
    echo "Your email or password is invalid!";
    }
}

?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>NAAM PROJECT</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="netflixLogin">
<div class="form form--login">

<form action="" method="post">
<h2 form__title>Sign In</h2>

            <div class="form__field">
            <label for="Email">Email</label>
            <!-- Name en id toevoegen -->
            <input type="text" id="Email" name="Email">
            </div>
            <div class="form__field">
            <label for="Password">Password</label>
            <!-- Name en id toevoegen -->
            <input type="password" id="Password" name="Password">
            <!-- Zie dat je het niet in label zet maar in input -->
            </div>

            <div class="form__field">
            <!-- type button gaan we veranderen door submit anders refresht het niet -->
            <input type="submit" value="Sign in" class="btn btn--primary">	
            <!-- <input type="checkbox" id="rememberMe"><label for="rememberMe" class="label__inline">Remember me</label> -->
            </div>
        </form>
    </div>
</div>
</body>
</html>