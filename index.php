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

};



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
?>
