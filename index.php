<?php

require("init.php");
require("helpers.php");

if (isset($_SESSION['user'])) {
    header("Location: /feed.php");
}

$errors  = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = checkRequiredFields(['login', 'password']);

    $userLogin = mysqli_real_escape_string($connect, trim($_POST['login']));
    $userPassword = mysqli_real_escape_string($connect, trim($_POST['password']));
    $result = mysqli_query($connect, "SELECT email, password, id, avatar, login FROM users WHERE email = '$userLogin'");
    $user = $result ? mysqli_fetch_array($result, MYSQLI_ASSOC) : null;
    
    if ($user && !count($errors)) {
        if (password_verify($userPassword, $user['password'])) {
            $_SESSION['user'] = $user['id'];
            $_SESSION['avatar'] = !empty($user['avatar']) && file_exists('uploads/' . $user['avatar']) ? $user['avatar'] : 'icon-input-user.svg';
            $_SESSION['login'] = $user['login'];

            header("Location: feed.php");
        } else {
            $errors['password'] = "Вы ввели неверный email/пароль";
        }
    } elseif (!empty($userLogin) && (empty($user['email']) || $user['email'] !== $userLogin)) {
        $errors['login'] = "Вы ввели неверный email/пароль";
    }
}

$layoutContent = include_template('index.php', ['form' => $_POST, 'errors' => $errors]);
print($layoutContent);
