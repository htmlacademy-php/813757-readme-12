<?php

require("init.php");

if (!isset($_SESSION['user'])) {
    header("Location: /index.php");
}

$user = $_SESSION['user'];

if (isset($_GET['author_id'])) {
    $authorId = $_GET['author_id'];
    $dbQuery = mysqli_query($connect, "SELECT * FROM users WHERE id = $authorId");

    $follower = mysqli_query($connect, "SELECT follower FROM subscription WHERE follower = $user AND user_id = $authorId");
    $followerInformation = mysqli_fetch_array($follower, MYSQLI_ASSOC);

    if (!$dbQuery) {
        exit("Ошибка подготовки запроса: " . mysqli_error($connect));
    }

    if (mysqli_num_rows($dbQuery) > 0 && $followerInformation['follower'] !== $user) {
        $subscription = mysqli_query($connect, "INSERT INTO subscription SET follower = $user, user_id = $authorId");
    } else {
        $unsubscribe = mysqli_query($connect, "DELETE FROM subscription WHERE user_id = $authorId AND follower = $user");
    }

    header("Location: profile.php?author_id=$authorId");
}

