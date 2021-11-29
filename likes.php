<?php

require("helpers.php");
require("init.php");

if (!isset($_SESSION['user'])) {
    header("Location: /index.php");
}

$user = $_SESSION['user'];

$postId = (int) filter_input(INPUT_GET, 'post-id');

$query = mysqli_query($connect, "SELECT id, author_id FROM posts WHERE id = $postId");
$results = array_column(mysqli_fetch_all($query, MYSQLI_ASSOC), 'author_id');

if (!$query) {
    exit("Ошибка подготовки запроса: " . mysqli_error($connect));
}

$dbUsersId = mysqli_query($connect, "SELECT user_id FROM likes WHERE liked_post = $postId");
$usersId = array_column(mysqli_fetch_all($dbUsersId, MYSQLI_ASSOC), 'user_id');

if (mysqli_num_rows($query) > 0 && !in_array($user, $usersId) && !in_array($user, $results)) {
    $like = "INSERT INTO likes (user_id, liked_post) VALUES (?, ?)";
    mysqli_stmt_execute(db_get_prepare_stmt($connect, $like, [$user, $postId]));
} else {
    $like = mysqli_query($connect, "DELETE FROM likes WHERE liked_post = $postId AND user_id = $user");
}

if (isset($_SERVER['HTTP_REFERER'])) {
    $previous = $_SERVER['HTTP_REFERER'];
    header("Location: $previous");
}
