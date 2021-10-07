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

$content = include_template('feed.php', [
]);

$pageInformation = [
    'userName' => $userInformation['login'],
    'avatar' => $userInformation['avatar'],
    'title' => 'readme: моя лента',
    'menuElements' => $menuElements,
    'content' => $content,
    'russianValues'=> RUSSIAN_VALUES
];

$layout = include_template('layout.php', $pageInformation);

print($layout);
