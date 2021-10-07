<?php

require("helpers.php");
require("init.php");

$errors  = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = checkRequiredFields(['login', 'password']);

    $userLogin = mysqli_real_escape_string($connect, trim($_POST['login']));
    $userPassword = mysqli_real_escape_string($connect, trim($_POST['password']));
    $result = mysqli_query($connect, "SELECT email, password, id FROM users WHERE email = '$userLogin'");
    $user = $result ? mysqli_fetch_array($result, MYSQLI_ASSOC) : null;

    if ($user && !count($errors)) {
        if (password_verify($userPassword, $user['password'])) {
            $_SESSION['user'] = $user['id'];
            header("Location: feed.php");
        } else {
            $errors['password'] = "Вы ввели неверный пароль";
         }
    } elseif (!empty($userLogin) && $user['email'] !== $userLogin) {
        $errors['login'] = "Вы ввели неверный email";
    }
}

$layoutContent = include_template('index.php', ['form' => $_POST, 'errors' => $errors]);
print($layoutContent);
