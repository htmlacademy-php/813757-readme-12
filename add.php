<?php

require("helpers.php");
require("init.php");

$types = ['quote', 'text', 'photo', 'link', 'video'];
$russianTranslation = [
    'heading' => 'Заголовок',
    'cite-text' => 'Текст цитаты',
    'tags' => 'Теги',
    'post-link' => 'Ссылка',
    'photo-url' => 'Ссылка из интернета',
    'post-text' => 'Текст поста',
    'video-url' => 'Ссылка YOUTUBE',
    'error' => 'Выберите фото'
];

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

    $rules = [
        'heading' => isCorrectLength('heading', 10, 35),
        'tags' => getTags('tags'),
    ];

    switch ($formType) {
        case 'quote';
            $rules['cite-text'] = isCorrectLength('cite-text', 10, 70);
            break;

        case 'text':
            $rules['post-text'] = isCorrectLength('post-text', 10, 1000);
            break;

        case 'link':
            $rules['post-link'] = validateUrl($_POST['post-link']);
            break;

        case 'video':
            $rules['video-url'] = check_youtube_url($_POST['video-url']);
            break;

        case 'photo':
            if (!empty($_FILES['userpic-file-photo']['name'])) {
                $rules['userpic-file-photo'] = validateFile('userpic-file-photo');
                $tmp_dir = $_FILES['userpic-file-photo']['tmp_name'];
                $file_path = __DIR__.'/uploads/';
                $file_name = $_FILES['userpic-file-photo']['name'];
                move_uploaded_file($tmp_dir,$file_path.$file_name);
            } else {
                $rules['photo-url'] = validateUrl($_POST['photo-url']);
            }
            break;
    }

    foreach ($_POST as $key => $value) {
        if (isset($rules[$key]) && is_string($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule;
        }
    }

    $errors = array_filter($errors);

    if (empty($errors)) {
        $title = $_POST['heading'];
        $userId = 3;
        $tags_id = upsertTags($_POST['tags'], $connect);

        if (isset($_GET['form-type'])) {

            switch ($formType) {
                case 'quote':
                    $post_value = $_POST['cite-text'];
                    $content = " content='$post_value'";
                    $tipe_id = 1;
                    break;

                case 'text':
                    $post_value = $_POST['post-text'];
                    $content = " content='$post_value'";
                    $tipe_id = 2;
                    break;

                case 'link':
                    $post_value = $_POST['post-link'];
                    $content = " website_link='$post_value'";
                    $tipe_id = 4;
                    break;

                case 'video':
                    $post_value = $_POST['video-url'];
                    $content = " video='$post_value'";
                    $tipe_id = 5;
                    break;

                case 'photo':
                    $tipe_id = 3;

                    if (!empty($_FILES['userpic-file-photo']['name'])) {
                        $photo_file = $_FILES['userpic-file-photo']['name'];
                        $content = " image='uploads/".$photo_file."'";
                    } else {
                        $post_url = $_POST['photo-url'];
                        $content = " image='$post_url'";
                    }
                    break;
            }

            $query = "INSERT INTO posts SET title='$title',".$content.", type_id=$tipe_id, author_id=$userId";

            $result = mysqli_query($connect, $query);

            if (!$result) {
                print("Ошибка подготовки запроса: " . mysqli_error($connect));
                exit();
            } else {
                $last_id = mysqli_insert_id($connect);
                foreach ($tags_id as $tag_id) {
                    $query = "INSERT INTO posts_hashtags SET post_id=$last_id, hashtag_id=$tag_id";
                    mysqli_query($connect, $query);
                }
            }

            header("Location: post.php?post-id=".$last_id);

        }
    }
}

$pageInformation = [
    'userName' => 'Ivan',
    'title' => 'readme: добавление публикации',
    'is_auth' => rand(0, 1),
    'content_type' => $contentType,
    'form_type' => $formType,
    'types' => $types,
    'errors' => $errors,
    'russianTranslation' => $russianTranslation
];

$layout = include_template('adding-post.php', $pageInformation);

print($layout);
