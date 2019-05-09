<?php
    require_once 'bootstrap.php';

    $errorMessage = '';

    if (!empty($_POST)) {
        $user = new User();
        $user->setEmail($_POST['email']);
        $user->setFirstName($_POST['firstName']);
        $user->setLastName($_POST['lastName']);
        $user->setPw($_POST['password']);
        $user->setPwConfirm($_POST['passwordConfirmation']);
        $user->setAvatar($_FILES['avatar']['name']);
        $user->setAvatarType($_FILES['avatar']['type']);
        $user->setAvatarTmpName($_FILES['avatar']['tmp_name']);
        $user->setProfileText($_POST['profileText']);

        $email = $user->getEmail();
        $firstName = $user->getFirstName();
        $lastName = $user->getLastName();
        $password = $user->getPw();
        $passwordConfirmation = $user->getPwConfirm();
        $avatar = $user->getAvatar();
        $avatarType = $user->getAvatarType();
        $avatarTmpName = $user->getAvatarTmpName();
        $profileText = $user->getProfileText();

        if (!$user->filledIn($email)) {
            global $errorMessage;
            $errorMessage = 'You did not fill in your email.';
        } elseif (!$user->filledIn($firstName)) {
            global $errorMessage;
            $errorMessage = 'You did not fill in your first name.';
        } elseif (!$user->filledIn($lastName)) {
            global $errorMessage;
            $errorMessage = 'You did not fill in your last name.';
        } elseif (!$user->filledIn($password)) {
            global $errorMessage;
            $errorMessage = "You didn't enter a password.";
        } elseif (!$user->filledIn($passwordConfirmation)) {
            global $errorMessage;
            $errorMessage = 'You need to confirm your password.';
        } elseif (!$user->itemsAreEqual($password, $passwordConfirmation)) {
            global $errorMessage;
            $errorMessage = "These passwords don't match.";
        } elseif ($user->checkIfEmailAlreadyExists($email)) {
            global $errorMessage;
            $errorMessage = "There's already an account with this email, try logging in instead or use a different email.";
        } elseif (!$user->isPwStrongEnough($password)) {
            global $errorMessage;
            $errorMessage = 'This password is not strong enough.';
        } elseif (!$user->checkIfFileTypeIsImage($avatarType)) {
            global $errorMessage;
            $errorMessage = 'The uploaded file for your avatar is not an image.';
        } else {
            $user->copyAvatartoImageFolder($avatar);
            $result = $user->register();
            if ($result) {
                header('Location: login.php');
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./css/styleLogin.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,900" rel="stylesheet">
    <title>PROJECT</title>
</head>
<body>
    <div class="leftColumn">
    <form action="" method="post" enctype="multipart/form-data" id="form">
        <h2 form__title>Register your account</h2>

        <?php if (!empty($errorMessage)): ?>
            <p id="errorMessage"><?php echo $errorMessage; ?></p>
        <?php endif; ?>

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
        <br>
        <label for="profileText">Write your profile text here</label>
        <input type="text" name="profileText" id="profileText">
        <br>
        <input type="submit" value="Register" id="submitBtn">
    </form>
    <a href="login.php">Or log in instead</a>
    </div>
</body>
</html>