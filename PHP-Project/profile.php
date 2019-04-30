<?php
    require_once("bootstrap.php");

    $user = new User();
    $user->setId($_GET['id']);

    $conn = Db::getInstance();
    $statement = $conn->prepare("SELECT * FROM users WHERE id = :id");
    $statement->bindValue(":id", $user->getId());
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    $user->setFirstName($result['firstName']);
    $user->setLastName($result['lastName']);

    //$userPosts = Db::simpleFetch("SELECT * FROM photos WHERE uploader = " . $user->getId());
    //$userPost->setPosts($userPosts['']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <?php include_once("includes/nav.inc.php");?>
    <h1><?php echo $user->getFirstName() . " " . $user->getLastName();?></h1>

</body>
</html>