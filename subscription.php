<?php

require("helpers.php");
require("init.php");
require_once("send-mail.php");

if (!isset($_SESSION['user'])) {
    header("Location: /index.php");
}

$user = $_SESSION['user'];
$login = $_SESSION['login'];

if (isset($_GET['author_id'])) {
    $authorId = $_GET['author_id'];
    $dbQuery = mysqli_query($connect, "SELECT * FROM users WHERE id = $authorId");
    $author = mysqli_fetch_array($dbQuery, MYSQLI_ASSOC);

    $follower = mysqli_query($connect, "SELECT follower FROM subscription WHERE follower = $user AND user_id = $authorId");
    $followerInformation = mysqli_fetch_array($follower, MYSQLI_ASSOC);

    if (!$dbQuery) {
        exit("Ошибка подготовки запроса: " . mysqli_error($connect));
    }

    if (mysqli_num_rows($dbQuery) > 0 && $followerInformation['follower'] !== $user) {
        $subscription = mysqli_query($connect, "INSERT INTO subscription SET follower = $user, user_id = $authorId");

        $message = new Swift_Message();
        $message->setSubject('У вас новый подписчик');
        $message->setFrom(['keks@phpdemo.ru' => 'Кекс']);
        $message->setTo([$author['email'] => $author['login']]);
        $message->setBody("<div>Здравствуйте, {$author['login']}. На вас подписался новый пользователь {$login}, оформившего подписку%. Вот ссылка на его профиль: <a href=\"http://813757-readme-12/profile.php?author_id={$user}\">{$login}</a></div>", 'text/html');
        $mailer->send($message);
    } else {
        $unsubscribe = mysqli_query($connect, "DELETE FROM subscription WHERE user_id = $authorId AND follower = $user");
    }

    header("Location: profile.php?author_id=$authorId");
}
