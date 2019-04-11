<?php
    require_once("classes/User.class.php");

    $errorMessage = "";

    if(!empty($_POST)){
		$user = new User();
        $user->setEmail($_POST['email']);
        $user->setFirstName($_POST['firstName']);
        $user->setLastName($_POST['lastName']);
		$user->setPw($_POST['password']);
        $user->setPwConfirm($_POST['passwordConfirmation']);
        $user->setAvatar($_FILES['avatar'];)

        $email = $user->getEmail();
        $firstName = $user->getFirstName();
        $lastName = $user->getLastName();
        $password = $user->getPw();
        $passwordConfirmation = $user->getPwConfirm();

        if(!$user->filledIn($email)){
            //echo "you did not fill in your email";
            global $errorMessage;
            $errorMessage = "you did not fill in your email";
        } else if(!$user->filledIn($firstName)){
            //echo "you did not fill in your first name";
            global $errorMessage;
            $errorMessage = "you did not fill in your first name";
        } else if(!$user->filledIn($lastName)){
            //echo "you did not fill in your last name";
            global $errorMessage;
            $errorMessage = "you did not fill in your last name";
        } else if(!$user->filledIn($password)){
            //echo "you didn't enter a password";
            global $errorMessage;
            $errorMessage = "you didn't enter a password";
        } else if(!$user->filledIn($passwordConfirmation)){
            //echo "you need to confirm your password";
            global $errorMessage;
            $errorMessage = "you need to confirm your password";
        } else if(!$user->itemsAreEqual($password,$passwordConfirmation)){
            //echo "these passwords don't match";
            global $errorMessage;
            $errorMessage = "these passwords don't match";
        } else if($user->checkIfEmailAlreadyExists($email)){
            //echo "there's already an account with this email, try logging in instead or use a different email";
            global $errorMessage;
            $errorMessage = "there's already an account with this email, try logging in instead or use a different email";
        } else if(!$user->isPwStrongEnough($password)){
            //echo "this password is not strong enough";
            global $errorMessage;
            $errorMessage = "this password is not strong enough";
        } else {
            $result = $user->register();
            if($result){
                header("Location: login.php");
            }
        };
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

        <p><?php echo $errorMessage; ?></p>

        <label for="email">Email</label>
        <input type="text" id="email" name="email">
        <br>
        <label for="firstName">First Name</label>
        <input type="text" id="firstName" name="firstName">
        <br>
        <label for="lastName">Last Name</label>
        <input type="text" id="lastName" name="lastName">
        <br>
        <label for="password">Password</label>
        <input type="password" id="password" name="password">
        <br>
        <label for="password_confirmation">Confirm your password</label>
        <input type="password" id="passwordConfirmation" name="passwordConfirmation">
        <br>
        <label for="avatar">Choose an avatar</label>
        <input type="file" name="avatar" accept="image/*"/>
        <input type="submit" value="Sign up">
    </form>
    <a href="login.php">Log in instead</a>
</body>
</html>