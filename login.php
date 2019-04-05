
<?php

function canLogin ($email, $password){ 

    if( $email != "example@example.com" || $password != "bingewatch"){
    return false;
    } else {
    return true;
    }
}

if( !empty($_POST))	{
    // gegevens uit velden halen
    $email =  $_POST['Email'];
    $password = $_POST['Password'];

    // functie gebruiken die we aangemaakt hebben 
    if (canLogin($email, $password) === true){

        session_start();
        $_SESSION['email'] = $email; 
        header('Location: index.php');
        // hashen is niet nodig want je kan niets aanpassen op server
    } else {
   
        $error = true;
    }
}
?>

<!DOCTYPE html>
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

<!-- een errormelding maken -->
<?php if(isset($error)): ?>
<div class="form__error"> <!-- hidden toevoegen zodat de error melding weggaat -->
<p> Sorry, we can't log you in. Can you try again?</p>
</div>
<?php endif; ?>

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