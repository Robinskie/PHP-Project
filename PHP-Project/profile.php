<?php
    require_once 'bootstrap.php';

    $user = new User();
    $user->setId($_GET['id']);

    $conn = Db::getInstance();
    $statement = $conn->prepare('SELECT * FROM users WHERE id = :id');
    $statement->bindValue(':id', $user->getId());
    $statement->execute();
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    $user->setFirstName($result['firstName']);
    $user->setLastName($result['lastName']);
    $user->setProfileText($result['profileText']);
    $user->setAvatarTmpName($result['avatar']);

    $followersCountStatement = $conn->prepare('SELECT * FROM followers WHERE followedUser = :id');
    $followersCountStatement->bindValue(':id', $user->getId());
    $followersCountStatement->execute();
    $followersCount = $followersCountStatement->rowCount();

    $followingCountStatement = $conn->prepare('SELECT * FROM followers WHERE followingUser = :id');
    $followingCountStatement->bindValue(':id', $user->getId());
    $followingCountStatement->execute();
    $followingCount = $followingCountStatement->rowCount();

    $postsCountStatement = $conn->prepare('SELECT * FROM photos WHERE uploader = :id');
    $postsCountStatement->bindValue(':id', $user->getId());
    $postsCountStatement->execute();
    $postsCount = $postsCountStatement->rowCount();

    $userId = $_SESSION['userid'];
    $isFollowed = $user->getFollowState($userId);

    $userPostsStatement = $conn->prepare('SELECT id FROM photos WHERE uploader = :id');
    $userPostsStatement->bindValue(':id', $user->getId());
    $userPostsStatement->execute();
    $userPosts = $userPostsStatement->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./css/styleProfile.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,900" rel="stylesheet">
    <title>Document</title>
</head>
<body>

    <section id="sidebar">
        <div class="profileSection">
        <img class="profilePicture" src="<?php echo $user->getAvatarTmpName(); ?>" alt="Profile picture">
        <h1><?php echo $user->getFirstName().' '.$user->getLastName(); ?></h1>
        <p class="bio"><?php echo $user->getProfileText(); ?></p>
        <hr>
        </div>
        <div class="info">
        <h2>followers</h2>
        <p><span class="followersCount"><?php echo $followersCount; ?></span></p>
        <h2>following</h2>
        <p><?php echo $followingCount; ?></p>
        <h2>posts</h2>
        <p><?php echo $postsCount; ?></p>
        <hr>
        </div>

        <?php if ($isFollowed) {
    ?>
            <a href="#" id="followButton" class="followButton" data-id="<?php echo $user->getId(); ?>" data-followed=1>Unfollow</a>
        <?php
} else {
        ?>
            <a href="#" id="followButton" class="followButton" data-id="<?php echo $user->getId(); ?>" data-followed=0>Follow</a>
        <?php
    } ?>
    </section>

    <section id="content">
    <?php include_once 'includes/nav.inc.php'; ?>
        <?php foreach ($userPosts as $post): ?>
            <div class="photoBox">
                <a href="photo.php?id=<?php echo $post['id']; ?>"><img class="userPost" src="images/photos/<?php echo $post['id']; ?>_cropped.png" alt="">
            </div>
        <?php endforeach; ?>
    </section>
    


    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
    integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
    crossorigin="anonymous"></script>

  
    <script src="js/follow.js"></script>

</body>
</html>