<?php

require("helpers.php");
require("init.php");
require("constants.php");

if (!isset($_SESSION['user'])) {
    header("Location: /index.php");
}


$user = $_SESSION['user'];
$userAvatar = $_SESSION['avatar'];
$userLogin = $_SESSION['login'];
$data = [];
$data['user'] = $user;
$data['userAvatar'] = $userAvatar;

$result = mysqli_query($connect, "SELECT login, avatar FROM users WHERE id = '$user'");
$userInformation = mysqli_fetch_array($result, MYSQLI_ASSOC);

$dbRecipientsLink = "SELECT DISTINCT id, avatar, login FROM users
                     WHERE id IN (SELECT DISTINCT sender FROM messages WHERE recipient = $user)
                     OR id IN (SELECT DISTINCT recipient FROM messages WHERE sender = $user)";
$dbRecipients = mysqli_query($connect, $dbRecipientsLink);
$recipients = mysqli_fetch_all($dbRecipients, MYSQLI_ASSOC);
$data['recipients'] = $recipients;

$recipientsId = array_column($recipients, 'id');
$data['recipientsId'] = $recipientsId;

$lastMessages = [];
$recipientCountNewMessages = [];

foreach ($recipients as $recipient) {
    $dbLastMessagesLink = "SELECT message_date, content, sender FROM messages WHERE sender IN ($user, " . $recipient['id'] . ") AND recipient IN (" . $recipient['id'] . ", $user) ORDER BY message_date DESC LIMIT 1";
    $dbLastMessages = mysqli_query($connect, $dbLastMessagesLink);
    $dbLastMessage = mysqli_fetch_all($dbLastMessages, MYSQLI_ASSOC);
    $lastMessages[$recipient['id']] = $dbLastMessage[0];

    $dbRecipientNewMessages = mysqli_query($connect, "SELECT COUNT(flag) AS new_messages FROM messages WHERE flag = 1 AND (recipient = $user AND sender = " . $recipient['id'] . ")");
    $recipientNewMessages = mysqli_fetch_all($dbRecipientNewMessages, MYSQLI_ASSOC);
    $recipientCountNewMessages[$recipient['id']] = $recipientNewMessages[0];
}

$data['recipientCountNewMessages'] = $recipientCountNewMessages;

$data['lastMessages'] = $lastMessages;

$newMessages = getAllNewMessages($connect, $user);

if (isset($_GET['interlocutor_id'])) {
    $interlocutorId = (int) filter_input(INPUT_GET, 'interlocutor_id');
    $data['interlocutorId'] = $interlocutorId;

    $dbConversationsLink = "SELECT m.id, m.message_date, m.content, m.sender, m.recipient,
                            u.login AS sender_login, u.avatar AS sender_avatar,
                            us.login AS recipient_login, us.avatar AS recipient_avatar
                            FROM messages AS m
                            LEFT JOIN users AS u ON u.id = m.sender
                            LEFT JOIN users AS us ON us.id = m.recipient
                            WHERE
                            (sender = $user AND recipient = $interlocutorId) OR (sender = $interlocutorId AND recipient = $user)
                            ORDER BY message_date ASC";
    $dbConversations = mysqli_query($connect, $dbConversationsLink);
    $allConversations = mysqli_fetch_all($dbConversations, MYSQLI_ASSOC);
    $data['allConversations'] = $allConversations;

    $updateConversation = "UPDATE messages SET flag = 0 WHERE sender = $interlocutorId";
    mysqli_query($connect, $updateConversation);

    $isExists = mysqli_query($connect, "SELECT * FROM users WHERE id = $interlocutorId");
    $newUser = mysqli_fetch_array($isExists, MYSQLI_ASSOC);
    $data['newUser'] = $newUser;

    if (!$isExists) {
        exit("Ошибка подготовки запроса: " . mysqli_error($connect));
    }

    $error = "";

    if (isset($_POST['message']) && mysqli_num_rows($isExists) > 0) {
        $message = mysqli_real_escape_string($connect, trim($_POST['message']));

        $error = isCorrectLength('message', 2, 2000);

        if (empty($error)) {
            $currentDate = new DateTime("", new DateTimeZone("Europe/Moscow"));
            $formatCurrentDate = $currentDate->format('Y-m-d H:i:s');
            $insertMessage = "INSERT INTO messages (message_date, content, sender, recipient, flag) VALUES (?, ?, ?, ?, ?)";

            mysqli_stmt_execute(db_get_prepare_stmt($connect, $insertMessage, [$formatCurrentDate, $message, $user, $interlocutorId, 1]));

            header("Location: messages.php?interlocutor_id=" . $interlocutorId);
        }
    }

    $data['error'] = $error;
}

$content = include_template('messages.php', $data);

$pageInformation = [
  'userName' => $userInformation['login'],
  'avatar' => $userInformation['avatar'],
  'title' => 'readme: личные сообщения',
  'menuElements' => MENU_ELEMENTS,
  'content' => $content,
  'russianValues'=> RUSSIAN_VALUES,
  'userAvatar' => $userAvatar,
  'user' => $user,
  'newMessages' => $newMessages
];

$layout = include_template('layout.php', $pageInformation);

print($layout);
