<section class="profile__subscriptions tabs__content tabs__content--active">
  <h2 class="visually-hidden">Подписки</h2>
  <ul class="profile__subscriptions-list">
    <?php foreach($userFollowers as $userFollower): ?>
    <li class="post-mini post-mini--photo post user">
      <div class="post-mini__user-info user__info">
        <div class="post-mini__avatar user__avatar">
          <a class="user__avatar-link" href="profile.php?author_id=<?= $userFollower['user_id'] ?>">
            <img class="post-mini__picture user__picture" src="uploads/<?= !empty($userFollower['avatar']) && file_exists('uploads/' . $userFollower['avatar']) ? $userFollower['avatar'] : 'icon-input-user.svg' ?>" alt="Аватар пользователя">
          </a>
        </div>
        <div class="post-mini__name-wrapper user__name-wrapper">
          <a class="post-mini__name user__name" href="profile.php?author_id=<?= $userFollower['user_id'] ?>">
            <span><?= $userFollower['login'] ?></span>
          </a>
          <time class="post-mini__time user__additional" datetime="<?= htmlspecialchars($userFollower['registration_date']) ?>"><?= getRelativeFormat(htmlspecialchars($userFollower['registration_date']), "на сайте") ?>на сайте</time>
        </div>
      </div>
      <div class="post-mini__rating user__rating">
        <p class="post-mini__rating-item user__rating-item user__rating-item--publications">
          <span class="post-mini__rating-amount user__rating-amount"><?= $countPosts[$userFollower['user_id']] ?></span>
          <span class="post-mini__rating-text user__rating-text">публикаций</span>
        </p>
        <p class="post-mini__rating-item user__rating-item user__rating-item--subscribers">
          <span class="post-mini__rating-amount user__rating-amount"><?= htmlspecialchars($countFollowers[$userFollower['user_id']]) ?></span>
          <span class="post-mini__rating-text user__rating-text">подписчиков</span>
        </p>
      </div>
      <div class="post-mini__user-buttons user__buttons">
        <?php if ($userFollower['follower'] !== $user): ?>
        <a class="post-mini__user-button user__button user__button--subscription button button--main" href="subscription.php?author_id=<?= $userFollower['user_id'] ?>">Подписаться</a>
        <?php else: ?>
        <a class="post-mini__user-button user__button user__button--subscription button button--quartz" href="subscription.php?author_id=<?= $userFollower['user_id'] ?>">Отписаться</a>
        <?php endif ?>
      </div>
    </li>
    <?php endforeach ?>
  </ul>
</section>
