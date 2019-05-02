<nav id="nav">
    <?php
        if ($_SERVER['PHP_SELF'] != '/PHP-Project/PHP-Project/index.php') {
            ?><a href="index.php">Home</a><?php
        }
    ?>
    <a href="profile.php?id=<?php echo $userId = $_SESSION['userid']; ?>">Profile</a>
    <a href="logout.php">Log out</a>
</nav>