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

$types = ['quote', 'text', 'photo', 'link', 'video'];
$menuElements = ['popular', 'feed', 'messages'];

if (!$connect) {
    print("Ошибка подключения: " . mysqli_connect_error());
}

$contentQuery = "SELECT * FROM content_type";
$contentResult = mysqli_query($connect, $contentQuery);

if (!$contentResult) {
    print("Ошибка подготовки запроса: " . mysqli_error($connect));
    exit();
}

$contentType = mysqli_fetch_all($contentResult, MYSQLI_ASSOC);

$query = "SELECT p.*, ct.content_title, ct.icon_class, u.login, u.avatar FROM posts AS p JOIN content_type ct ON p.type_id = ct.id JOIN users u ON p.author_id = u.id WHERE 1";

if (isset($_GET['type_id'])) {
    $type_id = intval(filter_input(INPUT_GET, 'type_id'));
    $query .= " AND p.type_id = $type_id";
}

$sort = isset($_GET['sort']) ? filter_input(INPUT_GET, 'sort') : "p.views_number";

$order = isset($_GET['ord']) ? filter_input(INPUT_GET, 'ord') : "DESC";

$query .= " ORDER BY $sort $order LIMIT 6";

$result = mysqli_query($connect, $query);

if (!$result) {
    print("Ошибка подготовки запроса: " . mysqli_error($connect));
    exit();
}

mysqli_close($connect);

$posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

$content = include_template('main.php', [
    'cardsInformation' => $posts,
    'types' => $types,
    'contentType' => $contentType,
    'ord' => $order,
    'sort' => $sort,
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
