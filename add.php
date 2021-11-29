<?php

require("helpers.php");
require("init.php");
require("constants.php");
require_once("send-mail.php");

if (!isset($_SESSION['user'])) {
    header("Location: /index.php");
}

$user = $_SESSION['user'];
$userAvatar = $_SESSION['avatar'];
$userLogin = $_SESSION['login'];

$result = mysqli_query($connect, "SELECT login, avatar FROM users WHERE id = '$user'");
$userInformation = mysqli_fetch_array($result, MYSQLI_ASSOC);
$newMessages = getAllNewMessages($connect, $user);

$russianTranslation = [
    'heading' => 'Заголовок',
    'cite-text' => 'Текст цитаты',
    'tags' => 'Теги',
    'post-link' => 'Ссылка',
    'photo-url' => 'Ссылка из интернета',
    'post-text' => 'Текст поста',
    'video-url' => 'Ссылка YOUTUBE',
    'userpic-file-photo' => 'Выберите фото'
];

$formType = $_GET['form-type'] ?? "";
$contentTypes = mysqli_fetch_all(getContent($connect, "content_type"), MYSQLI_ASSOC);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rules = [
        'heading' => isCorrectLength('heading', 10, 35),
        'tags' => getTags($_POST['tags']),
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
                $errors['userpic-file-photo'] = validateFile('userpic-file-photo');
            } else {
                $rules['photo-url'] = validateUrl($_POST['photo-url']);
                $validExtensions = ['image/png', 'image/jpeg', 'image/gif'];
                $link = mysqli_real_escape_string($connect, $_POST['photo-url']);
                $baseName = pathinfo($link, PATHINFO_BASENAME);

                if (empty($rules['photo-url'])) {
                    copy($link,  $_SERVER['DOCUMENT_ROOT'].'/uploads/'. $baseName);

                    if (!in_array(mime_content_type("uploads/" . $baseName), $validExtensions)) {
                        $rules['photo-url'] = "По введенной вами ссылке файл не найден";
                        unlink("uploads/".$baseName);
                    }
                }
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
        $title = mysqli_real_escape_string($connect, $_POST['heading']);
        $tagsAntiInjection = mysqli_real_escape_string($connect, $_POST['tags']);
        $tagsId = upsertTags($tagsAntiInjection, $connect);

        if (isset($_GET['form-type'])) {
            switch ($formType) {
                case 'quote':
                    $antiInjection = mysqli_real_escape_string($connect, $_POST['cite-text']);
                    $content = " content='$antiInjection'";
                    $typeId = 1;
                    break;

                case 'text':
                    $antiInjection = mysqli_real_escape_string($connect, $_POST['post-text']);
                    $content = " content='$antiInjection'";
                    $typeId = 2;
                    break;

                case 'link':
                    $antiInjection = mysqli_real_escape_string($connect, $_POST['post-link']);
                    $content = " website_link='$antiInjection'";
                    $typeId = 4;
                    break;

                case 'video':
                    $antiInjection = mysqli_real_escape_string($connect, $_POST['video-url']);
                    $content = " video='$antiInjection'";
                    $typeId = 5;
                    break;

                case 'photo':
                    $typeId = 3;

                    if (!empty($_FILES['userpic-file-photo']['name'])) {
                        $fileName = $_FILES['userpic-file-photo']['name'];
                        $tmpDir = $_FILES["userpic-file-photo"]["tmp_name"];
                        $filePath = __DIR__.'/uploads/';
                        move_uploaded_file($tmpDir, $filePath.$fileName);
                        $content = " image='$fileName'";
                    } else {
                        $link = mysqli_real_escape_string($connect, $_POST['photo-url']);
                        $baseName = pathinfo($link, PATHINFO_BASENAME);
                        $content = " image='$baseName'";
                    }
                    break;
            }

            $query = "INSERT INTO posts SET title='$title',".$content.", type_id=$typeId, author_id='$user'";
            $result = mysqli_query($connect, $query);

            if (!$result) {
                exit("Ошибка подготовки запроса: " . mysqli_error($connect));
            } else {
                $lastId = mysqli_insert_id($connect);
                foreach ($tagsId as $tagId) {
                    $query = "INSERT INTO posts_hashtags SET post_id=$lastId, hashtag_id=$tagId";
                    mysqli_query($connect, $query);
                }

                $message = new Swift_Message();
                $message->setSubject("Новая публикация от пользователя {$userInformation['login']} автора поста {$title}");
            }

            $dbFollowers = mysqli_query($connect, "SELECT * FROM users WHERE id IN (SELECT follower FROM subscription WHERE user_id = " . $user . ")");
            $followers = mysqli_fetch_all($dbFollowers, MYSQLI_ASSOC);

            if ($followers) {
                foreach ($followers as $follower) {
                    $message = new Swift_Message();
                    $message->setSubject("Новая публикация от пользователя {$userInformation['login']} автора поста {$title}");
                    $message->setFrom(['keks@phpdemo.ru' => 'Кекс']);
                    $message->setTo([$follower['email'] => $follower['login']]);
                    $message->setBody("<div>Здравствуйте, {$follower['login']}. Пользователь {$user} автора поста {$title} только что опубликовал новую запись „{$title}“. Посмотрите её на странице пользователя: <a href=\"http://813757-readme-12/profile.php?author_id={$user}\">{$userLogin}</a></div>", 'text/html');
                    $result = $mailer->send($message);
                }
            }

            header("Location: post.php?post-id=".$lastId);

        }
    }
}

$content = include_template('adding-post.php', [
    'contentTypes' => $contentTypes,
    'formType' => $formType,
    'types' => TYPES,
    'errors' => $errors,
    'russianTranslation' => $russianTranslation
]);

$pageInformation = [
    'userName' => $userInformation['login'],
    'avatar' => $userInformation['avatar'],
    'title' => 'readme: добавление публикации',
    'menuElements' => MENU_ELEMENTS,
    'content' => $content,
    'russianValues'=> RUSSIAN_VALUES,
    'userAvatar' => $userAvatar,
    'newMessages' => $newMessages
];

$layout = include_template('layout.php', $pageInformation);

print($layout);
