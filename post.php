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

if (isset($_GET['post-id'])) {
    $postId = intval(filter_input(INPUT_GET, 'post-id'));
    $query = "SELECT p.*, ct.content_title, ct.icon_class, u.login, u.avatar, ph.post_id, h.hashtag FROM posts AS p JOIN content_type ct ON p.type_id = ct.id JOIN users u ON p.author_id = u.id JOIN posts_hashtags ph ON ph.post_id = p.id JOIN hashtags h ON h.id = ph.hashtag_id WHERE p.id = $postId";
    $results = mysqli_query($connect, $query);

    if (!$results) {
        print("Ошибка подготовки запроса: " . mysqli_error($connect));
        exit();
    }

    mysqli_close($connect);

    $dbPosts = mysqli_fetch_all($results, MYSQLI_ASSOC);
    $tags = array_column($dbPosts, 'hashtag');
    $post = $dbPosts[0];
}

$content = include_template('post-details.php', [
    'post' => $post,
    'types' => TYPES,
    'tags' => $tags,
    'userName' => $userInformation['login'],
    'avatar' => $userInformation['avatar'],
]);

$pageInformation = [
    'userName' => $userInformation['login'],
    'avatar' => $userInformation['avatar'],
    'title' => 'readme: популярное',
    'content' => $content,
    'menuElements' => MENU_ELEMENTS,
    'russianValues'=> RUSSIAN_VALUES
];

$layout = include_template('layout.php', $pageInformation);

print($layout);
