<?php

require("helpers.php");
require("init.php");
require("constants.php");

if (!isset($_SESSION['user'])) {
    header("Location: /index.php");
}

$user = $_SESSION['user'];
$result = mysqli_query($connect, "SELECT login, avatar FROM users WHERE id = '$user'");
$userInformation = mysqli_fetch_array($result, MYSQLI_ASSOC);

if (empty($userInformation['avatar'])) {
    $userInformation['avatar'] = "icon-input-user.svg";
}

$menuElements = ['popular', 'feed', 'messages'];

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
    'menuElements' => $menuElements,
    'RUSSIAN_VALUES'=> RUSSIAN_VALUES
];

$layout = include_template('layout.php', $pageInformation);

print($layout);
