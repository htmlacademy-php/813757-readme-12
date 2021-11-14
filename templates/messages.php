<main class="page__main page__main--messages">
  <h1 class="visually-hidden">Личные сообщения</h1>
  <section class="messages tabs">
    <h2 class="visually-hidden">Сообщения</h2>
    <div class="messages__contacts">
      <ul class="messages__contacts-list tabs__list">
      <?php if (isset($interlocutorId) && !in_array($interlocutorId, $recipientsId)): ?>
        <li class="messages__contacts-item>
            <a class="messages__contacts-tab<?= $activeClass = isset($_GET['interlocutor_id']) && $_GET['interlocutor_id'] === $newUser['id'] ? ' messages__contacts-tab--active tabs__item tabs__item--active' : '' ?>" href="messages.php?interlocutor_id=<?= $newUser['id'] ?>">
                <div class="messages__avatar-wrapper">
                    <img class="messages__avatar" src="uploads/<?= !empty($newUser['avatar']) && file_exists('uploads/' . $newUser['avatar']) ? htmlspecialchars($newUser['avatar']) : 'icon-input-user.svg' ?>" alt="Аватар пользователя">
                </div>
                <div class="messages__info">
                    <span class="messages__contact-name">
                        <?= $newUser['login'] ?>
                    </span>
                </div>
            </a>
        </li>
      <?php endif ?>
      <?php if (!empty($recipients)): ?>
        <?php foreach ($recipients as $recipient): ?>
        <li class="messages__contacts-item<?= $recipientCountNewMessages[$recipient['id']]['new_messages'] !== '0' ? ' messages__contacts-item--new' : '' ?>">
            <a class="messages__contacts-tab<?= $activeClass = isset($_GET['interlocutor_id']) && $_GET['interlocutor_id'] === $recipient['id'] ? ' messages__contacts-tab--active tabs__item tabs__item--active' : '' ?>" href="messages.php?interlocutor_id=<?= $recipient['id'] ?>">
                <div class="messages__avatar-wrapper">
                    <img class="messages__avatar" src="uploads/<?= !empty($recipient['avatar']) && file_exists('uploads/' . $recipient['avatar']) ? htmlspecialchars($recipient['avatar']) : 'icon-input-user.svg' ?>" alt="Аватар пользователя">
                    <?php if ($recipientCountNewMessages[$recipient['id']]['new_messages'] !== '0'): ?>
                    <i class="messages__indicator"><?= $recipientCountNewMessages[$recipient['id']]['new_messages'] ?></i>
                    <?php endif ?>
                </div>
                <div class="messages__info">
                    <span class="messages__contact-name">
                        <?= $recipient['login'] ?>
                    </span>
                    <div class="messages__preview">
                        <p class="messages__preview-text">
                            <?= $lastMessages[$recipient['id']]['sender'] !== $user ? getPreviewText($lastMessages[$recipient['id']]['content']) : 'Вы: ' . getPreviewText($lastMessages[$recipient['id']]['content']) ?>
                        </p>
                        <time class="messages__preview-time" datetime="<?= $lastMessages[$recipient['id']]['message_date']?>">
                            <?= formatTime($lastMessages[$recipient['id']]['message_date']) ?>
                        </time>
                    </div>
                </div>
            </a>
        </li>
        <?php endforeach ?>
      <?php endif ?>
      </ul>
    </div>
    <div class="messages__chat">
      <?php if (isset($_GET['interlocutor_id'])): ?>
      <div class="messages__chat-wrapper">
        <ul class="messages__list tabs__content <?= isset($_GET['interlocutor_id']) ? 'tabs__content--active' : ''?>">
        <?php if (!empty($allConversations)): ?>
            <?php foreach ($allConversations as $message): ?>
            <li class="messages__item<?= $message['sender'] === $user ? ' messages__item--my' : '' ?>">
                <div class="messages__info-wrapper">
                    <div class="messages__item-avatar">
                        <a class="messages__author-link" href="#">
                            <img class="messages__avatar" src="uploads/<?= !empty($message['sender_avatar']) && file_exists('uploads/' . $message['sender_avatar']) ? htmlspecialchars($message['sender_avatar']) : 'icon-input-user.svg' ?>" alt="Аватар пользователя">
                        </a>
                    </div>
                    <div class="messages__item-info">
                        <a class="messages__author" href="#">
                            <?= htmlspecialchars($message['sender_login']) ?>
                        </a>
                        <time class="messages__time" datetime="<?= htmlspecialchars($message['message_date']) ?>">
                            <?= getRelativeFormat(htmlspecialchars($message['message_date'])) ?>
                        </time>
                    </div>
                </div>
                <p class="messages__text">
                    <?= htmlspecialchars($message['content']) ?>
                </p>
            </li>
            <?php endforeach ?>
        <?php endif ?>
      </div>
      <div class="comments">
        <form class="comments__form form" action="#" method="post">
          <div class="comments__my-avatar">
            <img class="comments__picture" src="uploads/<?= $userAvatar ?>" alt="Аватар пользователя">
          </div>
          <div class="form__input-section<?= !empty($error) ? ' form__input-section--error' : '' ?>">
            <textarea class="comments__textarea form__textarea form__input" name="message" placeholder="Ваше сообщение"><?= $_POST['message'] ?? '' ?></textarea>
            <label class="visually-hidden">Ваше сообщение</label>
            <button class="form__error-button button" type="button">!</button>
            <div class="form__error-text">
              <h3 class="form__error-title">Ошибка валидации</h3>
              <p class="form__error-desc"><?= $error ?></p>
            </div>
          </div>
          <input class="visually-hidden" id="<?= htmlspecialchars($_GET['interlocutor_id'])?>" type="text" name="<?= htmlspecialchars($_GET['interlocutor_id']) ?>" value="" placeholder="">
          <button class="comments__submit button button--green" type="submit">Отправить</button>
        </form>
      </div>
      <?php endif ?>
    </div>
  </section>
</main>
