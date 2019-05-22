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

        global $errorMessage;

        if (!$user->filledIn($email)) {
            $errorMessage = 'You did not fill in your email.';
        } elseif (!$user->filledIn($firstName)) {
            $errorMessage = 'You did not fill in your first name.';
        } elseif (!$user->filledIn($lastName)) {
            $errorMessage = 'You did not fill in your last name.';
        } elseif (!$user->filledIn($password)) {
            $errorMessage = "You didn't enter a password.";
        } elseif (!$user->filledIn($passwordConfirmation)) {
            $errorMessage = 'You need to confirm your password.';
        } elseif (!$user->itemsAreEqual($password, $passwordConfirmation)) {
            $errorMessage = "These passwords don't match.";
        } elseif ($user->checkIfEmailAlreadyExists($email)) {
            $errorMessage = "There's already an account with this email, try logging in instead or use a different email.";
        } elseif (!$user->isPwStrongEnough($password)) {
            $errorMessage = 'This password is not strong enough.';
        } elseif (!$user->checkIfFileTypeIsImage($avatarType)) {
            $errorMessage = 'The uploaded file for your avatar is not an image.';
        } else {
            $user->copyAvatartoImageFolder($avatar);
            $user->register();
            $userid = $user->getUserId($_POST['email']);
            $_SESSION['userid'] = $userid;
            header('Location: index.php');
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
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/site.webmanifest">
    <title>Register - Zoogram</title>
</head>
<body>
    <div class="leftColumn">
    <form action="" method="post" enctype="multipart/form-data" id="form">
        <h2 form__title>Register your account</h2>

        <?php if (!empty($errorMessage)): ?>
            <p id="errorMessage"><?php echo $errorMessage; ?></p>
        <?php endif; ?>

        <label for="email">Email</label>
        <input type="text" id="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>">
        <br>
        <label for="firstName">First Name</label>
        <input type="text" id="firstName" name="firstName" value="<?php echo isset($_POST['firstName']) ? $_POST['firstName'] : ''; ?>">
        <br>
        <label for="lastName">Last Name</label>
        <input type="text" id="lastName" name="lastName" value="<?php echo isset($_POST['lastName']) ? $_POST['lastName'] : ''; ?>">
        <br>
        <label for="password">Password</label>
        <input type="password" id="password" name="password">
        <br>
        <label for="password_confirmation">Confirm your password</label>
        <input type="password" id="passwordConfirmation" name="passwordConfirmation">
        <br>
        <label for="avatar">Upload a profile picture (required)</label>
        <input type="file" name="avatar" accept="image/*" required>
        <br>
        <label for="profileText">Write your profile text here</label>
        <input type="text" name="profileText" id="profileText" value="<?php echo isset($_POST['profileText']) ? $_POST['profileText'] : ''; ?>">
        <br>
        <input type="submit" value="Register" id="submitBtn">
    </form>
    <a href="login.php">Or log in instead</a>
    </div>
</body>
</html>