<?php
    require_once 'bootstrap.php';

    $user = new User();
    $user->setId($_GET['id']);
    $user->setData();

    $userId = $_SESSION['userid'];

    $followersCount = $user->getFollowersCount($_GET['id']);
    $followingCount = $user->getFollowingCount($_GET['id']);
    $isFollowed = $user->getFollowState($userId);

    $userPosts = $user->getUserPosts($_GET['id']);
    $postsCount = $user->getUserPostsCount($_GET['id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./css/styleProfile.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,500,900" rel="stylesheet">
    <link rel="stylesheet" href="https://cssgram-cssgram.netdna-ssl.com/cssgram.min.css">
    <title>Document</title>
</head>
<body>
    <?php include_once 'includes/nav.inc.php'; ?>

    <section id="sidebar">
        <div class="profileSection">
        <img class="profilePicture" src="<?php echo $user->getAvatar(); ?>" alt="Profile picture">
        <h1><?php echo $user->getFullName(); ?></h1>
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
        <?php foreach ($userPosts as $post): ?>
            <div class="photoBox">
                <a href="photo.php?id=<?php echo $post['id']; ?>"><img id="userPost" class="<?php echo $post['photoFilter']; ?>" src="images/photos/<?php echo $post['id']; ?>_cropped.png" alt="">
            </div>
        <?php endforeach; ?>
    </section>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
    integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
    crossorigin="anonymous"></script>

  
    <script src="js/follow.js"></script>

</body>
</html>