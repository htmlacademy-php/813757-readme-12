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

$contentTypes = mysqli_fetch_all(getContent($connect, "content_type"), MYSQLI_ASSOC);

$dbAuthorsRow = mysqli_query($connect, "SELECT user_id FROM subscription WHERE follower = $user");
$authors = array_column(mysqli_fetch_all($dbAuthorsRow, MYSQLI_ASSOC), 'user_id');

$dbPosts = "SELECT p.*, ct.content_title, ct.icon_class, u.login, u.avatar,
            (SELECT COUNT(*) as count FROM likes WHERE liked_post = p.id)  as likes,
            (SELECT COUNT(*) as count FROM comments WHERE post_id = p.id) as comments,
            (SELECT COUNT(*) as count FROM posts WHERE original_id = p.id) as reposts
            FROM posts AS p
            JOIN content_type ct ON p.type_id = ct.id
            JOIN users u ON p.author_id = u.id
            WHERE p.author_id IN ('" . implode("', '", $authors) . "')";

if (isset($_GET['type_id'])) {
    $typeId = (int) filter_input(INPUT_GET, 'type_id');
    $dbPosts .= " AND p.type_id = $typeId";
}

$dbPostsRow = mysqli_query($connect, $dbPosts);
$posts = mysqli_fetch_all($dbPostsRow, MYSQLI_ASSOC);

$content = include_template('feed.php', [
    'posts' => $posts,
    'types' => TYPES,
    'contentTypes' => $contentTypes
]);

$pageInformation = [
    'userName' => $userInformation['login'],
    'avatar' => $userInformation['avatar'],
    'title' => 'readme: моя лента',
    'menuElements' => MENU_ELEMENTS,
    'content' => $content,
    'russianValues'=> RUSSIAN_VALUES
];

$layout = include_template('layout.php', $pageInformation);

print($layout);
