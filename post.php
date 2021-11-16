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
$newMessages = getAllNewMessages($connect);

if (isset($_GET['post-id'])) {
    $postId = (int) filter_input(INPUT_GET, 'post-id');
    $queryPost = "SELECT p.*, ct.content_title, ct.icon_class, u.login, u.avatar, (SELECT COUNT(*) as count FROM likes WHERE liked_post = p.id)  as likes FROM posts AS p JOIN content_type ct ON p.type_id = ct.id JOIN users u ON p.author_id = u.id WHERE p.id = " . $postId;

    $results = mysqli_query($connect, $queryPost);

    if (!$results) {
        exit("Ошибка подготовки запроса: " . mysqli_error($connect));
    }

    $post = mysqli_fetch_array($results, MYSQLI_ASSOC);

    $dbCommentsCount = mysqli_query($connect, "SELECT COUNT(*) as count FROM comments WHERE post_id = " . $post['id']);
    $commentsCount = mysqli_fetch_array($dbCommentsCount, MYSQLI_ASSOC);

    $dbCommentsLink = "SELECT c.creation_date, c.content, u.avatar, u.login FROM comments AS c JOIN users u ON c.author_id = u.id WHERE post_id = " . $post['id'];
    $dbComments = mysqli_query($connect, $dbCommentsLink);

    if (!isset($_GET['show_all_comments'])) {
        $dbCommentsLink .= " LIMIT 3";
    }

    $dbComments = mysqli_query($connect, $dbCommentsLink);
    $comments = mysqli_fetch_all($dbComments, MYSQLI_ASSOC);

    $dbFollower = mysqli_query($connect, "SELECT * FROM subscription WHERE follower = $user AND user_id = " . $post['author_id']);
    $followerInformation = mysqli_fetch_array($dbFollower, MYSQLI_ASSOC);
    $followerCounts = mysqli_num_rows($dbFollower);

    $dbPostsCount = mysqli_query($connect, "SELECT * FROM posts WHERE author_id = " . $post['author_id']);
    $postsCount = mysqli_num_rows($dbPostsCount);

    $repostsRaw = mysqli_query($connect, "SELECT COUNT(*) as count FROM posts WHERE original_id = $postId");
    $repostsCount = mysqli_fetch_array($repostsRaw, MYSQLI_ASSOC);

    $dbTags = mysqli_query($connect, "SELECT h.hashtag FROM posts_hashtags as ph JOIN hashtags as h ON h.id = ph.hashtag_id WHERE ph.post_id = ".$post['id']);
    $hashtagsArray = mysqli_fetch_all($dbTags, MYSQLI_ASSOC);
    $hashtags = array_column($hashtagsArray, 'hashtag');

    $isExists = mysqli_query($connect, "SELECT * FROM posts WHERE id = $postId");

    if (!$isExists) {
        exit("Ошибка подготовки запроса: " . mysqli_error($connect));
    }

    $error = "";

    if (isset($_POST['comment']) && mysqli_num_rows($isExists) > 0) {
        $comment = mysqli_real_escape_string($connect, trim($_POST['comment']));

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

    mysqli_close($connect);
}

$content = include_template('post-details.php', [
    'post' => $post,
    'types' => TYPES,
    'userName' => $userInformation['login'],
    'avatar' => $userInformation['avatar'],
    'comments' => $comments,
    'followerCounts' => $followerCounts,
    'postsCount' => $postsCount,
    'followerInformation' => $followerInformation,
    'user' => $user,
    'hashtags' => $hashtags,
    'commentsCount' => $commentsCount,
    'error' => $error,
    'repostsCount' => $repostsCount,
    'userAvatar' => $userAvatar
]);

$pageInformation = [
    'userName' => $userInformation['login'],
    'avatar' => $userInformation['avatar'],
    'title' => 'readme: популярное',
    'content' => $content,
    'menuElements' => MENU_ELEMENTS,
    'russianValues'=> RUSSIAN_VALUES,
    'userAvatar' => $userAvatar,
    'newMessages' => $newMessages
];

$layout = include_template('layout.php', $pageInformation);

print($layout);
