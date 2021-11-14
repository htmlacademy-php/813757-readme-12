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
$author = mysqli_query($connect, "SELECT * FROM users WHERE id = '".$_GET['author_id']."'");
$authorData = mysqli_fetch_array($author, MYSQLI_ASSOC);

$queryAuthorPosts = "SELECT p.*, ct.icon_class,
                    (SELECT COUNT(*) as count FROM likes WHERE liked_post = p.id) as likes,
                    (SELECT COUNT(*) as count FROM posts WHERE original_id = p.id) as reposts
                    FROM posts AS p
                    JOIN content_type ct ON p.type_id = ct.id
                    JOIN users u ON p.author_id = u.id
                    WHERE author_id = " . $_GET['author_id'];
$dbAuthorPosts = mysqli_query($connect, $queryAuthorPosts);
$authorPosts = mysqli_fetch_all($dbAuthorPosts, MYSQLI_ASSOC);
$postsCount = mysqli_num_rows($dbAuthorPosts);

$follower = mysqli_query($connect, "SELECT follower FROM subscription WHERE follower = $user AND user_id = " . $_GET['author_id']);
$followerInformation = mysqli_fetch_array($follower, MYSQLI_ASSOC);

$dbUserFollowers = mysqli_query($connect, "SELECT * FROM subscription AS s LEFT JOIN users u ON u.id = s.user_id WHERE follower = " . $_GET['author_id']);
$userFollowers = mysqli_fetch_all($dbUserFollowers, MYSQLI_ASSOC);

$countPosts = [];
$countFollowers = [];

foreach ($userFollowers as $userFollower) {
    $dbFollowerPostsCount = "SELECT COUNT(*) as count FROM posts AS p WHERE author_id = " . $userFollower['id'];
    $dbFollowerPosts =  mysqli_query($connect, $dbFollowerPostsCount);
    $dbPosts = mysqli_fetch_array($dbFollowerPosts, MYSQLI_ASSOC);
    $countPosts[$userFollower['id']] = $dbPosts['count'];

    $dbFollowerCount = mysqli_query($connect, "SELECT COUNT(*) as count FROM subscription WHERE user_id = " . $userFollower['id']);
    $dbFollowers = mysqli_fetch_array($dbFollowerCount, MYSQLI_ASSOC);
    $countFollowers[$userFollower['id']] = $dbFollowers['count'];
}

$dbFollowers = mysqli_query($connect, "SELECT COUNT(*) as count FROM subscription WHERE user_id = " . $_GET['author_id']);
$followerCounts = mysqli_fetch_array($dbFollowers, MYSQLI_ASSOC);

$postId = (int) filter_input(INPUT_GET, 'post-id');
$dbCommentsLink = "SELECT c.post_id, c.creation_date, c.content, u.avatar, u.login FROM comments AS c JOIN users u ON c.author_id = u.id WHERE post_id = $postId";
$dbComments = mysqli_query($connect, $dbCommentsLink);
$commentsCount = mysqli_num_rows($dbComments);

if (!isset($_GET['show_all_comments'])) {
    $dbCommentsLink .= " LIMIT 3";
}

$dbComments = mysqli_query($connect, $dbCommentsLink);
$comments = mysqli_fetch_all($dbComments, MYSQLI_ASSOC);
$commentsId = array_column($comments, 'post_id');

$isExists = mysqli_query($connect, "SELECT * FROM posts WHERE id = $postId");

if (!$isExists) {
    exit("Ошибка подготовки запроса: " . mysqli_error($connect));
}

$error = "";

if (isset($_POST['comment']) && mysqli_num_rows($isExists) > 0) {
    $comment = mysqli_real_escape_string($connect, trim($_POST['comment']));
    $authorId = (int) filter_input(INPUT_GET, 'author_id');

    if (mb_strlen($comment) < 4) {
        $error = "Это поле обязательно к заполнению!!!";
    }

    if (empty($error)) {

        $currentDate = new DateTime("", new DateTimeZone("Europe/Moscow"));
        $formatCurrentDate = $currentDate->format('Y-m-d H:i:s');
        $insertComment = "INSERT INTO comments (creation_date, content, author_id, post_id) VALUES (?, ?, ?, ?)";

        mysqli_stmt_execute(db_get_prepare_stmt($connect, $insertComment, [$formatCurrentDate, $comment, $user, $postId]));

        header("Location: profile.php?author_id=" . $authorId);
    }
}

$dbLikesLink = "SELECT p.id, p.image, p.video, u.avatar, u.login, ct.icon_class
                FROM posts AS p
                LEFT JOIN likes AS l ON l.liked_post = p.id
                LEFT JOIN users AS u ON u.id = l.user_id
                LEFT JOIN content_type ct ON p.type_id = ct.id
                WHERE p.author_id = " . $_GET['author_id'];
$dbLikes = mysqli_query($connect, $dbLikesLink);
$likedPosts = mysqli_fetch_all($dbLikes, MYSQLI_ASSOC);
$hashtags = [];

foreach($authorPosts as $authorPost) {
    $dbHashtags = "SELECT h.hashtag FROM posts_hashtags as ph JOIN hashtags as h ON h.id = ph.hashtag_id WHERE ph.post_id = ".$authorPost['id'];
    $dbTags = mysqli_query($connect, $dbHashtags);
    $hashtagsArray = mysqli_fetch_all($dbTags, MYSQLI_ASSOC);
    $hashtags[$authorPost['id']] = $hashtagsArray;
}

$mainProfileContent = "profile-posts.php";

if (isset($_GET['profile-content']) && $_GET['profile-content'] === "likes") {
    $mainProfileContent = "profile-likes.php";
} elseif (isset($_GET['profile-content']) && $_GET['profile-content'] === "subscriptions") {
    $mainProfileContent = "profile-subscriptions.php";
}

$profileContent = include_template($mainProfileContent, [
    'avatar' => $userInformation['avatar'],
    'authorPosts' => $authorPosts,
    'types' => TYPES,
    'hashtags' => $hashtags,
    'user' => $user,
    'comments' => $comments,
    'commentsId' => $commentsId,
    'commentsCount' => $commentsCount,
    'error' => $error,
    'likedPosts' => $likedPosts,
    'userFollowers' => $userFollowers,
    'countPosts' => $countPosts,
    'countFollowers' => $countFollowers,
    'userAvatar' => $userAvatar
]);

$content = include_template('profile.php', [
    'avatar' => $userInformation['avatar'],
    'authorData' => $authorData,
    'authorPosts' => $authorPosts,
    'user' => $user,
    'followerInformation' => $followerInformation,
    'followerCounts' => $followerCounts,
    'postsCount' => $postsCount,
    'profileContent' => $profileContent
]);

$pageInformation = [
    'userName' => $userInformation['login'],
    'avatar' => $userInformation['avatar'],
    'title' => 'readme: профиль',
    'menuElements' => MENU_ELEMENTS,
    'content' => $content,
    'russianValues'=> RUSSIAN_VALUES,
    'user' => $user,
    'userAvatar' => $userAvatar,
    'newMessages' => getAllNewMessages($connect, 'messages')
];

$layout = include_template('layout.php', $pageInformation);

print($layout);
