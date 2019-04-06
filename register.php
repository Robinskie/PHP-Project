<?php
    require_once("classes/User.class.php");

    if(!empty($_POST)){
		$user = new User();
		$user->setEmail($_POST['email']);
		$user->setPw($_POST['password']);
		$user->setPwConfirm($_POST['passwordConfirmation']);
		$result = $user->register();
        
        if($result){
            header("Location: login.php");
        }
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PROJECT</title>
</head>
<body>
    <form action="" method="post">
        <h2 form__title>Sign up for an account</h2>
        <div class="hidden">
			<p>Something went wrong.</p>
        </div>
        
        <label for="email">Email</label>
        <input type="text" id="email" name="email">
        
        <label for="password">Password</label>
        <input type="password" id="password" name="password">
        
        <label for="password_confirmation">Confirm your password</label>
        <input type="password" id="passwordConfirmation" name="passwordConfirmation">

        <input type="submit" value="Sign up">
    </form>
</body>
</html>