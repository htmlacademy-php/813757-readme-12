<section class="profile__likes tabs__content tabs__content--active">
  <h2 class="visually-hidden">Лайки</h2>
  <ul class="profile__likes-list">
    <?php foreach ($likedPosts as $likedPost): ?>
    <li class="post-mini post-mini--<?= $likedPost['icon_class'] ?> post user">
      <div class="post-mini__user-info user__info">
        <div class="post-mini__avatar user__avatar">
          <a class="user__avatar-link" href="#">
            <img class="post-mini__picture user__picture" src="uploads/<?= !empty($likedPost['avatar']) && file_exists('uploads/' . $likedPost['avatar']) ? htmlspecialchars($likedPost['avatar']) : 'icon-input-user.svg' ?>" alt="Аватар пользователя">
          </a>
        </div>
        <div class="post-mini__name-wrapper user__name-wrapper">
          <a class="post-mini__name user__name" href="#">
            <span><?= $likedPost['login'] ?></span>
          </a>
          <div class="post-mini__action">
            <span class="post-mini__activity user__additional">Лайкнул вашу публикацию</span>
            <time class="post-mini__time user__additional" datetime="2014-03-20T20:20">5 минут назад</time>
          </div>
        </div>
      </div>
      <div class="post-mini__preview">
        <a class="post-mini__link" href="post.php?post-id=<?= htmlspecialchars($likedPost['id']) ?>" title="Перейти на публикацию">
          <?php if ($likedPost['icon_class'] === $types[2]): ?>
          <div class="post-mini__image-wrapper">
            <img class="post-mini__image" src="uploads/<?= htmlspecialchars($likedPost['image']) ?>" width="109" height="109" alt="Превью публикации">
          </div>
          <span class="visually-hidden">Фото</span>
          <?php elseif ($likedPost['icon_class'] === $types[1]): ?>
          <span class="visually-hidden">Текст</span>
          <svg class="post-mini__preview-icon" width="20" height="21">
            <use xlink:href="#icon-filter-text"></use>
          </svg>
          <?php elseif ($likedPost['icon_class'] === $types[4]): ?>
          <div class="post-mini__image-wrapper">
            <?= embed_youtube_cover(htmlspecialchars($likedPost['video']), 109, 109) ?>
            <span class="post-mini__play-big">
              <svg class="post-mini__play-big-icon" width="12" height="13">
                <use xlink:href="#icon-video-play-big"></use>
              </svg>
            </span>
          </div>
          <span class="visually-hidden">Видео</span>
          <?php elseif ($likedPost['icon_class'] === $types[0]): ?>
          <span class="visually-hidden">Цитата</span>
          <svg class="post-mini__preview-icon" width="21" height="20">
            <use xlink:href="#icon-filter-quote"></use>
          </svg>
          <?php elseif ($likedPost['icon_class'] === $types[3]): ?>
          <span class="visually-hidden">Ссылка</span>
          <svg class="post-mini__preview-icon" width="21" height="18">
            <use xlink:href="#icon-filter-link"></use>
          </svg>
          <?php endif ?>
        </a>
      </div>
    </li>
    <?php endforeach ?>
  </ul>
</section>
