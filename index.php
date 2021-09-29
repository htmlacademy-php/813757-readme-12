<?php

require("helpers.php");
require("init.php");

$form = $_POST;
$errors  = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requiredFields = ['login', 'password'];
    $errors = checkRequiredFields($requiredFields);

    $login = mysqli_real_escape_string($connect, $_POST['login']);
    $result = mysqli_query($connect, "SELECT * FROM users WHERE login = '$login'");
    $user = $result ? mysqli_fetch_array($result, MYSQLI_ASSOC) : null;

    if (!count($errors) && $user) {
        if (password_verify($form['password'], $user['password'])) {
            $_SESSION['user'] = $user;
            header("Location: feed.php");
        } else {
            $errors['password'] = "Неверный пароль";
         }
    } elseif (!empty($_POST['login']) && $user['login'] !== $_POST['login']) {
        $errors['login'] = "Пользователь с логином {$_POST['login']} не найден";
    }
}

$layoutContent = include_template('index.php', ['form' => $form, 'errors' => $errors]);
print($layoutContent);
