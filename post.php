<?php

require("helpers.php");
require("init.php");

if (!isset($_SESSION['user'])) {
    header("Location: /index.php");
}

$userInformation = $_SESSION['user'];
$myNavs = ['popular', 'feed', 'messages'];

if (isset($_GET['post-id'])) {
    $postId = intval(filter_input(INPUT_GET, 'post-id'));
    $query = "SELECT p.*, ct.content_title, ct.icon_class, u.login, u.avatar FROM posts AS p JOIN content_type ct ON p.type_id = ct.id JOIN users u ON p.author_id = u.id WHERE p.id = $postId";
    $result = mysqli_query($connect, $query);

    if (!$result) {
        print("Ошибка подготовки запроса: " . mysqli_error($connect));
        exit();
    }

    mysqli_close($connect);

    $post = mysqli_fetch_assoc($result);
}

$types = ['quote', 'text', 'photo', 'link', 'video'];

$content = include_template('post-details.php', [
    'post' => $post,
    'types' => $types
]);

$pageInformation = [
    'userName' => $userInformation['login'],
    'avatar' => $userInformation['avatar'],
    'title' => 'readme: популярное',
    'content' => $content,
    'myNavs' => $myNavs,
];

$layout = include_template('layout.php', $pageInformation);

print($layout);
