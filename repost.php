<?php

require("helpers.php");
require("init.php");

if (!isset($_SESSION['user'])) {
    header("Location: /index.php");
}

$user = $_SESSION['user'];

if (isset($_GET['post-id'])) {
    $postId = intval(filter_input(INPUT_GET, 'post-id'));
    $dbPost = mysqli_query($connect, "SELECT * FROM posts WHERE id = $postId");
    $post = mysqli_fetch_array($dbPost, MYSQLI_ASSOC);

    if ($user === $post['author_id']) {
        $link = "/post.php?post-id=" . $post['id'];
        header("Location: $link");
        die();
    }

    if (mysqli_num_rows($dbPost) > 0) {
        $currentDate = new DateTime("", new DateTimeZone("Europe/Moscow"));
        $formatCurrentDate = $currentDate->format('Y-m-d H:i:s');

        $repostPost = "INSERT INTO posts (date_creation, title, content, quote_author, video, image, website_link, views_number, author_id, type_id, original_recoding_author, original_id, repost) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        mysqli_stmt_execute(db_get_prepare_stmt($connect, $repostPost, [$formatCurrentDate, $post['title'], $post['content'], $post['quote_author'], $post['video'], $post['image'], $post['website_link'], $post['views_number'], $user, $post['type_id'], $post['author_id'], $post['id'], 1]));

        header("Location: feed.php");
    }
}
