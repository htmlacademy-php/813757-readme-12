<?php

require("helpers.php");
require("init.php");

if (!isset($_SESSION['user'])) {
    header("Location: /index.php");
}

$userInformation = $_SESSION['user'];

$myNavs = ['popular', 'feed', 'messages'];

$content = include_template('feed.php', [
]);

$pageInformation = [
    'userName' => $userInformation['login'],
    'avatar' => $userInformation['avatar'],
    'title' => 'readme: моя лента',
    'myNavs' => $myNavs,
    'content' => $content
];

$layout = include_template('layout.php', $pageInformation);

print($layout);
