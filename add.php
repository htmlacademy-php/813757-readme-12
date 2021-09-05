<?php

require("helpers.php");
require("init.php");

$types = ['quote', 'text', 'photo', 'link', 'video'];

$formType = $_GET['form-type'] ?? "";

$contentQuery = "SELECT * FROM content_type";
$contentType = mysqli_query($connect, $contentQuery);

if (!$contentType) {
    print("Ошибка подготовки запроса: " . mysqli_error($connect));
    exit();
}

$contentType = mysqli_fetch_all($contentType, MYSQLI_ASSOC);
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $post = $_POST;

    $rules = [
        'heading' => isCorrectLength('heading', 10, 35),
        'tags' => getTags('tags'),
    ];

    if ($formType === 'quote') {
        $rules['cite-text'] = isCorrectLength('cite-text', 10, 70);
    } elseif ($formType === 'text') {
        $rules['post-text'] = isCorrectLength('post-text', 10, 1000);
    } elseif ($formType === 'link') {
        $rules['post-link'] = validateUrl('post-link');
    } elseif ($formType === 'video') {
        $rules['video-url'] = check_youtube_url(filter_var($_POST['video-url'], FILTER_VALIDATE_URL));
    } elseif ($formType === 'photo') {
        $rules['photo-url'] = validateUrl('photo-url');
    }

    foreach ($_POST as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule;
        }
    }
}

$errors = array_filter($errors);

$pageInformation = [
    'userName' => 'Ivan',
    'title' => 'readme: добавление публикации',
    'is_auth' => rand(0, 1),
    'content_type' => $contentType,
    'form_type' => $formType,
    'types' => $types,
    'errors' => $errors
];

$layout = include_template('adding-post.php', $pageInformation);

print($layout);
