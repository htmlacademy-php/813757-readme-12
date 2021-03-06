<?php

require("helpers.php");
require("init.php");
require("constants.php");

if (!isset($_SESSION['user'])) {
    header("Location: /index.php");
}

$user = $_SESSION['user'];
$userAvatar = $_SESSION['avatar'];
$result = mysqli_query($connect, "SELECT login, avatar FROM users WHERE id = '$user'");
$userInformation = mysqli_fetch_array($result, MYSQLI_ASSOC);

$newMessages = getAllNewMessages($connect, $user);
$back = $_SERVER['HTTP_REFERER'];

if (empty(trim($_GET['search']))) {
    $content = include_template('no-results.php', [
        "searchResult" => "Ваш запрос оказался пустым! Пожалуйста введите запрос!",
        "back" => $back
    ]);

    $pageInformation = [
        'userName' => $userInformation['login'],
        'avatar' => $userInformation['avatar'],
        'title' => 'readme: ничего не найдено',
        'menuElements' => MENU_ELEMENTS,
        'content' => $content,
        'russianValues'=> RUSSIAN_VALUES,
        'userAvatar' => $userAvatar
    ];

    $layout = include_template('layout.php', $pageInformation);

    die($layout);
}

$search = mysqli_real_escape_string($connect, trim($_GET['search']));

if (mb_substr($search, 0, 1) !== "#") {
    $query = "SELECT p.*, ct.content_title, ct.icon_class, u.login, u.avatar, (SELECT COUNT(*) as count FROM likes WHERE liked_post = p.id) as likes, (SELECT COUNT(*) as count FROM comments WHERE post_id = p.id) as comments FROM posts AS p JOIN content_type ct ON p.type_id = ct.id JOIN users u ON p.author_id = u.id WHERE MATCH(p.title, p.content) AGAINST ('$search*' IN BOOLEAN MODE) ORDER BY p.views_number DESC";
} else {
    $tag = mb_substr($search, 1);
    $query = "SELECT p.*, ct.content_title, ct.icon_class, u.login, u.avatar, ph.post_id, h.hashtag, (SELECT COUNT(*) as count FROM likes WHERE liked_post = p.id) as likes, (SELECT COUNT(*) as count FROM comments WHERE post_id = p.id) as comments FROM posts AS p JOIN content_type ct ON p.type_id = ct.id JOIN users u ON p.author_id = u.id JOIN posts_hashtags ph ON ph.post_id = p.id JOIN hashtags h ON h.id = ph.hashtag_id WHERE h.hashtag LIKE '%$tag%' ORDER BY p.date_creation DESC";
}

$results = mysqli_query($connect, $query);
$posts = mysqli_fetch_all($results, MYSQLI_ASSOC);

if (empty($posts)) {
    $content = include_template('no-results.php', [
        "searchResult" => "{$search}! По данному запросу ничего не найдено!",
        "back" => $back
    ]);


    $pageInformation = [
        'userName' => $userInformation['login'],
        'avatar' => $userInformation['avatar'],
        'title' => 'readme: ничего не найдено',
        'menuElements' => MENU_ELEMENTS,
        'content' => $content,
        'russianValues'=> RUSSIAN_VALUES,
        'newMessages' => $newMessages,
        'userAvatar' => $userAvatar
    ];

    $layout = include_template('layout.php', $pageInformation);

    die($layout);
}

$content = include_template('search-results.php', [
    'posts' => $posts,
    'types' => TYPES
]);

$pageInformation = [
    'userName' => $userInformation['login'],
    'avatar' => $userInformation['avatar'],
    'title' => 'readme: '.$search ,
    'menuElements' => MENU_ELEMENTS,
    'content' => $content,
    'russianValues'=> RUSSIAN_VALUES,
    'newMessages' => $newMessages,
    'userAvatar' => $userAvatar
];

$layout = include_template('layout.php', $pageInformation);

print($layout);
