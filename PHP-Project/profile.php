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
    $user->setProfileText($result['profileText']);
    $user->setAvatarTmpName($result['avatar']);

    $statement2 = $conn->prepare("SELECT * FROM followers WHERE followedUser = :id");
    $statement2->bindValue(":id", $user->getId());
    $statement2->execute();
    $followersCount = $statement2->rowCount();

    $statement3 = $conn->prepare("SELECT * FROM followers WHERE followingUser = :id");
    $statement3->bindValue(":id", $user->getId());
    $statement3->execute();
    $followingCount = $statement3->rowCount();

    $statement4 = $conn->prepare("SELECT * FROM photos WHERE uploader = :id");
    $statement4->bindValue(":id", $user->getId());
    $statement4->execute();
    $postsCount = $statement4->rowCount();

    $userId = $_SESSION['userid'];
    $isFollowed = $user->getFollowState($userId);

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
    <img src="<?php echo $user->getAvatarTmpName();?>" alt="Profile picture">
    <h1><?php echo $user->getFirstName() . " " . $user->getLastName();?></h1>
    <h2>Bio</h2>
    <p><?php echo $user->getProfileText();?></p>
    <h2>followers</h2>
    <p><span class="followersCount"><?php echo $followersCount;?></span></p>
    <h2>following</h2>
    <p><?php echo $followingCount;?></p>
    <h2>posts</h2>
    <p><?php echo $postsCount;?></p>

    <?php if($isFollowed) { ?>
        <a href="#" id="followButton" class="followButton" data-id="<?php echo $user->getId();?>" data-followed=1>Unfollow</a>
    <?php } else { ?>
        <a href="#" id="followButton" class="followButton" data-id="<?php echo $user->getId();?>" data-followed=0>Follow</a>
    <?php } ?>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
    integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
    crossorigin="anonymous"></script>

  
    <script src="js/follow.js"></script>

</body>
</html>