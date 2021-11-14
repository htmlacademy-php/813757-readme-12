<?php

require("helpers.php");
require("init.php");

$russianTranslation = [
    'email' => 'Электронная почта',
    'login' => 'Логин',
    'password' => 'Пароль',
    'password-repeat' => 'Повтор пароля',
    'error' => 'Выберите фото'
];

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = $_POST;

    $rules = [
        'email' => validateEmail('email'),
        'login' => isCorrectLength('login', 3, 10),
        'password' => isCorrectLength('password', 3, 20),
        'password-repeat' => compareValues($_POST['password'], $_POST['password-repeat'])
    ];

    foreach ($_POST as $key => $value) {
        if (isset($rules[$key]) && is_string($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule;
        }
    }

    $email = mysqli_real_escape_string($connect, $_POST['email']);
    $selectEmail = mysqli_query($connect, "SELECT email FROM users WHERE email = '$email'");

    if (!$selectEmail) {
        print("Ошибка подготовки запроса: " . mysqli_error($connect));
        exit();
    }

    $selectedEmail = mysqli_fetch_assoc($selectEmail);

    if ($selectedEmail) {
        $errors['email'] = "Пользователь с таким email уже существует!";
    }

    $errors = array_filter($errors);

    if (empty($errors)) {
        $tmpDir = $_FILES['userpic-file']['tmp_name'];
        $filePath = __DIR__.'/uploads/';
        $fileName = $_FILES['userpic-file']['name'];
        $email = trim($_POST['email']);
        $login = $_POST['login'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $avatar = $fileName;

        if (isset($_FILES['userpic-file'])) {
            $errors['error'] = validateFile('userpic-file');
        }

        move_uploaded_file($tmpDir,$filePath.$fileName);

        $query = "INSERT INTO users (email, login, password, avatar) VALUES (?, ?, ?, ?)";
        mysqli_stmt_execute(db_get_prepare_stmt($connect, $query, [$email, $login, $password, $avatar]));
        header("Location: index.php");
    }

}

$content = include_template('registration-page.php', [
    'errors' => $errors,
    'russianTranslation' => $russianTranslation
]);

$pageInformation = [
    'title' => 'readme: регистрация',
    'content' => $content
];

$layout = include_template('layout.php', $pageInformation);

print($layout);
