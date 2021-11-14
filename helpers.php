<?php
/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date): bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = []): object
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            } else {
                if (is_string($value)) {
                    $type = 's';
                } else {
                    if (is_double($value)) {
                        $type = 'd';
                    }
                }
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }
    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int)$number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template(string $name, array $data = []): string
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Функция проверяет доступно ли видео по ссылке на youtube
 * @param string $url ссылка на видео
 *
 * @return string Ошибку если валидация не прошла
 */
function check_youtube_url(string $url): bool
{
    $videoUrl = filter_var($url, FILTER_VALIDATE_URL);

    if (!$videoUrl) {
        return 'YOUTUBE ссылка неверна!';
    }

    $id = extract_youtube_id($url);

    set_error_handler(function () {}, E_WARNING);
    $headers = get_headers('https://www.youtube.com/oembed?format=json&url=http://www.youtube.com/watch?v=' . $id);
    restore_error_handler();

    if (!is_array($headers)) {
        return "Видео по такой ссылке не найдено. Проверьте ссылку на видео";
    }

    $err_flag = strpos($headers[0], '200') ? 200 : 404;

    if ($err_flag !== 200) {
        return "Видео по такой ссылке не найдено. Проверьте ссылку на видео";
    }

    return true;
}

/**
 * Возвращает код iframe для вставки youtube видео на страницу
 * @param string $youtube_url Ссылка на youtube видео
 * @return string
 */
function embed_youtube_video(string $youtube_url): string
{
    $res = "";
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $src = "https://www.youtube.com/embed/" . $id;
        $res = '<iframe width="760" height="400" src="' . $src . '" frameborder="0"></iframe>';
    }

    return $res;
}

/**
 * Возвращает img-тег с обложкой видео для вставки на страницу
 * @param string $youtube_url Ссылка на youtube видео
 * @return string
 */
function embed_youtube_cover(string $youtube_url, int $width=320, int $height=120): string
{
    $res = "";
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $src = sprintf("https://img.youtube.com/vi/%s/mqdefault.jpg", $id);
        $res = '<img alt="youtube cover" width="'.$width.'" height="'.$height.'" src="' . $src . '" />';
    }
    return $res;
}

/**
 * Извлекает из ссылки на youtube видео его уникальный ID
 * @param string $youtube_url Ссылка на youtube видео
 * @return string
 */
function extract_youtube_id(string $youtube_url): string
{
    $id = false;

    $parts = parse_url($youtube_url);

    if ($parts) {
        if ($parts['path'] == '/watch') {
            parse_str($parts['query'], $vars);
            $id = $vars['v'] ?? null;
        } else {
            if ($parts['host'] == 'youtu.be') {
                $id = substr($parts['path'], 1);
            }
        }
    }
    return $id;
}

/** генерирует случайную дату
 *
 * @param $index
 * @return false|string
 */
function generate_random_date(int $index): string
{
    $deltas = [['minutes' => 59], ['hours' => 23], ['days' => 6], ['weeks' => 4], ['months' => 11]];
    $dcnt = count($deltas);

    if ($index < 0) {
        $index = 0;
    }

    if ($index >= $dcnt) {
        $index = $dcnt - 1;
    }

    $delta = $deltas[$index];
    $timeval = rand(1, current($delta));
    $timename = key($delta);

    $ts = strtotime("$timeval $timename ago");
    $dt = date('Y-m-d H:i:s', $ts);

    return $dt;
}

/**
 * проверяет длину строки в input
 *
 * @param string $name
 * @param int $min
 * @param int $max
 * @return string
*/
function isCorrectLength(string $name, int $min, int $max): string {
    $len = mb_strlen(trim($_POST[$name]));

    if ($len < $min || $len > $max) {
        return "Значение должно быть от {$min} до {$max} символов";
    }
    return "";
}

/**
 * валидация поля с тегами
 *
 * @param string $tags
 * @return string
 */
function getTags(string $tags): string {
    $tagsArray = explode(" ", $tags);

    foreach ($tagsArray as $tag) {
        if (mb_strlen($tag) > 15) {
            return "Каждый тег должен состоять не более чем из 15 символов";
        }
    }

    return "";
}

/**
* валидация поля с ссылкой
* @param string $name
* @return string
*/
function validateUrl(string $link): string {
    if (!filter_var($link, FILTER_VALIDATE_URL)) {
        return 'Введите правильную ссылку! Типа https://www.htmlacademy.ru';
    }

    return "";
}

/**
* валидация загрузки файла
* @param string $file
* @return string
*/
function validateFile(string $file): string {
    $fileName = $_FILES[$file]['name'];
    $fileType = $_FILES[$file]['type'];
    $imageSize = $_FILES[$file]['size'];
    $filePath = __DIR__.'/uploads/';
    $validExtensions = ['image/png', 'image/jpeg', 'image/gif'];

    if (empty($fileName)) {
        return 'Пожалуйста, выберите изображение!';
    }

    if (in_array($fileType, $validExtensions)) {

        if (file_exists($filePath.$fileName)) {
            return 'Файл с таким именем существует!';
        }

        if ($imageSize > 5000000) {
            return 'Извините, ваш файл слишком велик!';
        }

    } else {
        return 'Выберите допустимый формат файла!(png, jpeg, gif)';
    }
    return "";
}

/**
 * проверяет наличие тега в БД и если он отсутствует добавляет его в БД
 *
 * @param array $inputTags
 * @param mysqli_connect $connect
 * @return array
 */
function upsertTags(string $inputTags, $connect): array {
    $tagsId = [];
    $tags = explode(' ', $inputTags);

    $query = "SELECT id, hashtag FROM hashtags WHERE hashtag IN ('" . implode("', '", $tags) . "')";

    $result = mysqli_query($connect, $query);

    if (!$result) {
        print("Ошибка подготовки запроса: " . mysqli_error($connect));
        exit();
    }

    $dbTagsRaw = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $newTags = [];

    $dbTags = array_column($dbTagsRaw, 'hashtag');

    foreach ($tags as $tag) {
        if (!in_array($tag, $dbTags)) {
            $newTags[] = $tag;
        }
    }

    $count = count($newTags);

    if ($count === 0) {
        foreach ($dbTagsRaw as $dbTag) {
            $tagsId[] = $dbTag['id'];
        }
    } elseif ($count >= 1) {
        foreach ($newTags as $newTag) {
            $query = "INSERT INTO hashtags (hashtag) VALUES ('" . $newTag . "')";
            mysqli_query($connect, $query);
            $lastId = mysqli_insert_id($connect);
            $tagsId[] = $lastId;
        }
    }

    return $tagsId;

}

/**
 * обрезает строку, если длина больше 300 символов
 *
 * @param string $string
 * @param int $limit
 * @return string Итоговый HTML
 */
function getCutString(string $string, int $limit = 300): string {
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

/**
 * обрезает строку, если длина больше 9 символов
 *
 * @param string $string
 * @param int $limit
 * @return string итоговый текст
 */

function getPreviewText(string $string, int $limit = 9): string {
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

        return $cutString . "...";
    }
    return $string . "...";
}

/**
 * возвращает разницу во времени
 *
 * @param string $date
 * @return string
 */
function getRelativeFormat(string $date, string $value="назад"): string {
    $currentDate = new DateTime("", new DateTimeZone("Europe/Moscow"));
    $publicationDate = new DateTime($date);
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
        $timeDifference = "{$months} {$month} {$value}";
    } elseif ($weeks > 0) {
        $timeDifference = "{$weeks} {$week} {$value}";
    } elseif ($days > 0) {
        $timeDifference = "{$days} {$day} {$value}";
    } elseif ($hours > 0) {
        $timeDifference = "{$hours} {$hour} {$value}";
    } elseif ($minutes > 0) {
        $timeDifference = "{$minutes} {$minute} {$value}";
    } else {
        $timeDifference = "только что";
    }

    return $timeDifference;
}

/**
 * функция выводит формат времени в зависимости от прошедшего времени
 * @param string время
 * @return string возвращает строку с необходимым форматом
*/
function formatTime($time): string
{
  $monthes = [1 => 'янв', 2 => 'фев', 3 => 'мар', 4 => 'апр', 5 => 'мая', 6 => 'июн', 7 => 'июл', 8 => 'авг', 9 => 'сен', 10 => 'окт', 11 => 'ноя', 12 => 'дек'];

  $date1 = new DateTime($time);
  $date2 = new DateTime();
  $difference = $date2->diff($date1);

  if ($difference->days > 1) {
    return $date1->format("j {$monthes[$date1->format('n')]}");
  }

  return $date1->format('H:i');
}

/**
 * возвращает дату публикации
 *
 * @param int $key
 * @return date
 */
function getPublicationTime(int $key): string {
    return (new DateTime(generate_random_date($key)))->format("c");
}

/**
 * возвращает формат даты
 *
 * @param int $key
 * @return format date
 */
function getFormatTime(int $key): string {
    return (new DateTime(generate_random_date($key)))->format("d.m.Y H:i");
}

/**
 * валидация поля email
 *
 * @param string $name
 * @return string
 */
function validateEmail(string $name): string {
    if (!filter_input(INPUT_POST, $name, FILTER_VALIDATE_EMAIL)) {
        return "Введите корректный email";
    }
    return "";
}

/**
 * сравнение значений полей
 *
 * @param string $firstValue
 * @param string $secondValue
 * @return string
 */
function compareValues(string $firstValue, string $secondValue): string {
    if ($firstValue !== $secondValue || empty($secondValue)) {
        return "Пароли не совпадают!";
    }
    return "";
}

/**
 * Проверяет поля на заполненность
 *
 * @param array $requiredFields
 * @return array
 */
function checkRequiredFields(array $requiredFields): array {
    $errors = [];

    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Поле не заполнено';
        }
    }

    return $errors;
}

/**
 * получает данные из БД, в случае ошибки подготовки запроса предупреждает об этом
 * @param mysqli_connect $connect
 * @param таблица в которой делается выборка из БД
 */
function getContent($connect, string $table) {
    $contentType = mysqli_query($connect, "SELECT * FROM $table");

    if (!$contentType) {
        print("Ошибка подготовки запроса: " . mysqli_error($connect));
        exit();
    }

    return $contentType;
}

/**
 * получает подсчет количества записей в таблице по условию
 * @param mysqli_connect $connect
 * @param $table таблица в которой делается выборка из БД
 * @return array массив с результатом
 */
function getAllNewMessages($connect, string $table): array
{
    $dbNewMessages = mysqli_query($connect, "SELECT COUNT(flag) AS new_messages FROM $table WHERE flag = 1");
    $newMessages = mysqli_fetch_array($dbNewMessages, MYSQLI_ASSOC);

    return $newMessages;
}
