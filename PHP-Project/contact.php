<?php
    require_once 'bootstrap.php';
    redirectIfLoggedOut();
    $errorMessage = '';

    $userId = $_SESSION['userid'];

    if (!empty($_POST)) {
        $message = 'Name: '.$_POST['name']."\n";
        $message .= 'Email: '.$_POST['email']."\n";
        $message .= 'Message: '.$_POST['message']."\n";
        mail('sarah.vandenheuvel@outlook.com', 'subject', $message);
    } else {
        $errorMessage = 'This is not correct, please try again';
    }

?><!DOCTYPE html>
<html lang="en">
<head>  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./css/styleContact.css">
    <link rel="stylesheet" href="https://cssgram-cssgram.netdna-ssl.com/cssgram.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,900" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/site.webmanifest">
    <title>Contact - Zoogram</title>

</head>
<body>
    <?php include_once 'includes/nav.inc.php'; ?>
    <div class="content">

    <h1>Contact us </h1>

    <!-- contactformulier -->
    <form name="contactform" action="" method="post">
                    <label for="name"></label>
                    <input type="text" name="name" id="name" class="name" placeholder="Your name" /><br>

                    <label for="email"></label>
                    <input type="text" name="email" id="email" class="email" placeholder="Your email"/><br>

                    <label for="message"></label>
                    <textarea id="message" name="message"  placeholder="Write your message"></textarea><br>

                <input type="submit" id="submit" class="send" value="Send" name="sendmessage" />
    </form>

</body>
</html>
