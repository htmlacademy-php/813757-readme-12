<?php

require("helpers.php");
require("init.php");

if (!isset($_SESSION['user'])) {
    header("Location: /index.php");
}

$user = $_SESSION['user'];

$postId = $_GET['post-id'];

$query = mysqli_query($connect, "SELECT id FROM posts WHERE id = $postId");

if (!$query) {
    print("Ошибка подготовки запроса: " . mysqli_error($connect));
    exit();
}

$dbUserId = mysqli_query($connect, "SELECT user_id FROM likes WHERE liked_post = $postId AND user_id = $user");
$userId = mysqli_fetch_array($dbUserId, MYSQLI_ASSOC);

if (mysqli_num_rows($query) > 0 && $userId['user_id'] !== $user) {
    $like = "INSERT INTO likes (user_id, liked_post) VALUES (?, ?)";
    mysqli_stmt_execute(db_get_prepare_stmt($connect, $like, [$user, $postId]));
} else {
    $like = mysqli_query($connect, "DELETE FROM likes WHERE liked_post = $postId AND user_id = $user");
}


if (isset($_SERVER['HTTP_REFERER'])) {
    $previous = $_SERVER['HTTP_REFERER'];
    header("Location: $previous");
}
