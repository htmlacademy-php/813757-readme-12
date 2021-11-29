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
$userData = mysqli_fetch_array($result, MYSQLI_ASSOC);

if (!$connect) {
    exit("Ошибка подключения: " . mysqli_connect_error());
}

$contentTypes = mysqli_fetch_all(getContent($connect, "content_type"), MYSQLI_ASSOC);

$query = "SELECT p.*, ct.content_title, ct.icon_class, u.login, u.avatar,
          (SELECT COUNT(*) as count FROM likes WHERE liked_post = p.id)  as likes,
          (SELECT COUNT(*) as count FROM comments WHERE post_id = p.id) as comments
          FROM posts AS p
          JOIN content_type ct ON p.type_id = ct.id
          JOIN users u ON p.author_id = u.id WHERE 1";

if (isset($_GET['type_id'])) {
    $typeId = (int) filter_input(INPUT_GET, 'type_id');
    $query .= " AND p.type_id = $typeId";
}

$postCounts = mysqli_query($connect, $query);
$postCount = count(mysqli_fetch_all($postCounts, MYSQLI_ASSOC));

$sort = isset($_GET['sort']) ? filter_input(INPUT_GET, 'sort') : "p.views_number";

$order = isset($_GET['order']) ? filter_input(INPUT_GET, 'order') : "DESC";
$notesOnPage = 6;
$query .= " ORDER BY $sort $order LIMIT $notesOnPage";

$page = $_GET['page'] ?? 1;

$from = ($page - 1) * $notesOnPage;

$query .= " OFFSET $from";

$result = mysqli_query($connect, $query);

$pageCount = ceil($postCount / $notesOnPage);

$next = $page;

if ($page < $pageCount) {
    $next = $page + 1;
}

$previous = 1;

if ($page > 1) {
    $previous = $page - 1;
}

if (!$result) {
    exit("Ошибка подготовки запроса: " . mysqli_error($connect));
}

$newMessages = getAllNewMessages($connect, $user);

mysqli_close($connect);

$posts = mysqli_fetch_all($result, MYSQLI_ASSOC);

$content = include_template('main.php', [
    'posts' => $posts,
    'types' => TYPES,
    'contentTypes' => $contentTypes,
    'order' => $order,
    'sort' => $sort,
    'postCount' => $postCount,
    'next' => $next,
    'previous' => $previous
]);

$pageInformation = [
    'userName' => $userData['login'],
    'avatar' => $userData['avatar'],
    'title' => 'readme: популярное',
    'content' => $content,
    'menuElements' => MENU_ELEMENTS,
    'russianValues'=> RUSSIAN_VALUES,
    'userAvatar' => $userAvatar,
    'user' => $user,
    'newMessages' => $newMessages
];

$layout = include_template('layout.php', $pageInformation);

print($layout);
