<main class="page__main page__main--profile">
  <h1 class="visually-hidden">Профиль</h1>
  <div class="profile profile--posts">
    <div class="profile__user-wrapper">
      <div class="profile__user user container">
        <div class="profile__user-info user__info">
          <div class="profile__avatar user__avatar">
            <img class="profile__picture user__picture" src="uploads/<?= !empty($authorData['avatar']) && file_exists('uploads/' . $authorData['avatar']) ? htmlspecialchars($authorData['avatar']) : "icon-input-user.svg" ?>" alt="Аватар пользователя">
          </div>
          <div class="profile__name-wrapper user__name-wrapper">
            <span class="profile__name user__name"><?= htmlspecialchars($authorData['login']) ?></span>
            <time class="profile__user-time user__time" datetime="<?= htmlspecialchars($authorData['registration_date']) ?>"><?= getRelativeFormat(htmlspecialchars($authorData['registration_date']), "на сайте") ?></time>
          </div>
        </div>
        <div class="profile__rating user__rating">
          <p class="profile__rating-item user__rating-item user__rating-item--publications">
            <span class="user__rating-amount"><?= $postsCount ?></span>
            <span class="profile__rating-text user__rating-text">публикаций</span>
          </p>
          <p class="profile__rating-item user__rating-item user__rating-item--subscribers">
            <span class="user__rating-amount"><?= $followerCounts['count'] ?></span>
            <span class="profile__rating-text user__rating-text">подписчиков</span>
          </p>
        </div>
        <div class="profile__user-buttons user__buttons">
          <?php if ($followerInformation['follower'] !== $user): ?>
          <a class="profile__user-button user__button user__button--subscription button button--main" href="subscription.php?author_id=<?= htmlspecialchars($_GET['author_id']) ?>">Подписаться</a>
          <?php  else: ?>
          <a class="profile__user-button user__button user__button--subscription button button--main" href="subscription.php?author_id=<?= htmlspecialchars($_GET['author_id']) ?>">Отписаться</a>
          <?php endif ?>
          <a class="profile__user-button user__button user__button--writing button button--green" href="<?= htmlspecialchars($_GET['author_id']) !== $user ? 'messages.php?interlocutor_id=' . htmlspecialchars($_GET['author_id']) : '' ?>">Сообщение</a>
        </div>
      </div>
    </div>
    <div class="profile__tabs-wrapper tabs">
      <div class="container">
        <div class="profile__tabs filters">
          <b class="profile__tabs-caption filters__caption">Показать:</b>
          <ul class="profile__tabs-list filters__list tabs__list">
            <li class="profile__tabs-item filters__item">
              <a class="profile__tabs-link filters__button<?= $activeClass = !isset($_GET['profile-content']) ? ' filters__button--active tabs__item tabs__item--active' : ''; ?> button" href="profile.php?author_id=<?= $_GET['author_id'] ?>">Посты</a>
            </li>
            <li class="profile__tabs-item filters__item">
              <a class="profile__tabs-link filters__button<?= $activeClass = isset($_GET['profile-content']) && $_GET['profile-content'] === 'likes' ? ' filters__button--active tabs__item tabs__item--active' : ''; ?> button" href="profile.php?author_id=<?= $_GET['author_id'] ?>&profile-content=likes">Лайки</a>
            </li>
            <li class="profile__tabs-item filters__item">
              <a class="profile__tabs-link filters__button <?= $activeClass = isset($_GET['profile-content']) && $_GET['profile-content'] === 'subscriptions' ? ' filters__button--active tabs__item tabs__item--active' : ''; ?> button" href="profile.php?author_id=<?= $_GET['author_id'] ?>&profile-content=subscriptions">Подписки</a>
            </li>
          </ul>
        </div>
        <div class="profile__tab-content">
          <?= $profileContent ?>
        </div>
      </div>
    </div>
  </div>
</main>
