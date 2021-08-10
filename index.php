<?php

require("helpers.php");

$types = ['post-quote', 'post-text', 'post-photo', 'post-link'];

$cards_information = [
    [
        'heading' => 'Цитата',
        'type' => 'post-quote',
        'content' => 'Мы в жизни любим только раз, а после ищем лишь похожих',
        'user_name' => 'Лариса',
        'avatar' => 'userpic-larisa-small.jpg'
    ],
    [
        'heading' => 'Игра престолов',
        'type' => 'post-text',
        'content' => 'Не могу дождаться начала финального сезона своего любимого сериала!',
        'user_name' => 'Владик',
        'avatar' => 'userpic.jpg'
    ],
    [
        'heading' => 'Наконец, обработал фотки!',
        'type' => 'post-photo',
        'content' => 'rock-medium.jpg',
        'user_name' => 'Виктор',
        'avatar' => 'userpic-mark.jpg'
    ],
    [
        'heading' => 'Моя мечта',
        'type' => 'post-photo',
        'content' => 'coast-medium.jpg',
        'user_name' => 'Лариса',
        'avatar' => 'userpic-larisa-small.jpg'
    ],
    [
        'heading' => 'Лучшие курсы',
        'type' => 'post-link',
        'content' => 'www.htmlacademy.ru',
        'user_name' => 'Владик',
        'avatar' => 'userpic.jpg'
    ]
];

function getCutString($string, $limit = 300) {

    if (mb_strlen($string, "UTF-8") > $limit) {

        $words = explode(" ", $string);
        $count = 0;
        $cutString = "";
        $newWords = [];

        foreach ($words as $elem) {
            $count += mb_strlen($elem, "UTF-8");

            if ($count < $limit) {
                array_push($newWords, $elem);
            };

        };

        $cutString = implode(" ", $newWords);

        return "<p>{$cutString}...</p><a class=\"post-text__more-link\" href=\"#\">Читать далее</a>";

    }

    return "<p>{$string}</p>";

}

function getPublicationTime($key) {
    return (new DateTime(generate_random_date($key)))->format("c");
}


function getFormatTime($key) {
    return (new DateTime(generate_random_date($key)))->format("d.m.Y H:i");
}

function getRelativeFormat($index) {
    $currentDate = new DateTime("", new DateTimeZone("Europe/Moscow"));
    $publicationDate = new DateTime(generate_random_date($index));
    $difference = $currentDate->diff($publicationDate);
    $minutes = $difference->i;
    $hours = $difference->h;
    $days = $difference->d;
    $weeks = floor($days / 7);
    $months = $difference->m;

    $minute = get_noun_plural_form($minutes, 'минуту', 'минуты', 'минут');
    $hour = get_noun_plural_form($hours, 'час', 'часа', 'часов');
    $day = get_noun_plural_form($days, 'день', 'дня', 'дней');
    $week = get_noun_plural_form($weeks, 'неделю', 'недели', 'недель');
    $month = get_noun_plural_form($months, 'месяц', 'месяца', 'месяцев');

    if ($months > 0) {
        $timeDifference = "{$months} {$month} назад";
    } elseif ($weeks > 0) {
        $timeDifference = "{$weeks} {$week} назад";
    } elseif ($days > 0) {
        $timeDifference = "{$days} {$day} назад";
    } elseif ($hours > 0) {
        $timeDifference = "{$hours} {$hour} назад";
    } elseif ($minutes > 0) {
        $timeDifference = "{$minutes} {$minute} назад";
    }

    return $timeDifference;
}

$content = include_template('main.php', [
    'cards_information' => $cards_information,
    'types' => $types
]);

$pageInformation = [
    'userName' => 'Ivan',
    'title' => 'readme: популярное',
    'content' => $content,
    'is_auth' => rand(0, 1),
];

$layout = include_template('layout.php', $pageInformation);

print($layout);
