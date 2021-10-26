<?php

require("init.php");

$postId = (int) filter_input(INPUT_GET, 'post-id');
$isExists = mysqli_query($connect, "SELECT * FROM posts WHERE id = $postId");

if (!$isExists) {
    print("Ошибка подготовки запроса: " . mysqli_error($connect));
    exit();
}

$error = "";

if (isset($_POST['comment']) && mysqli_num_rows($isExists) > 0) {
    $comment = trim($_POST['comment']);

    if (mb_strlen($comment) < 4) {
        $error = "Это поле обязательно к заполнению!!!";
    }

    if (empty($error)) {

        $currentDate = new DateTime("", new DateTimeZone("Europe/Moscow"));
        $formatCurrentDate = $currentDate->format('Y-m-d H:i:s');
        $insertComment = "INSERT INTO comments (creation_date, content, author_id, post_id) VALUES (?, ?, ?, ?)";

        mysqli_stmt_execute(db_get_prepare_stmt($connect, $insertComment, [$formatCurrentDate, $comment, $user, $postId]));

        header("Location: profile.php?author_id=".$post['author_id']);
    }
}
