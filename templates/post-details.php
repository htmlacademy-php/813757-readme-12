<main class="page__main page__main--publication">
  <div class="container">
    <h1 class="page__title page__title--publication"><?= htmlspecialchars($post['title']) ?></h1>
    <section class="post-details">
      <h2 class="visually-hidden">Публикация</h2>
      <div class="post-details__wrapper post-photo">
        <div class="post-details__main-block post post--details">
          <?php if (isset($_GET) && $post['icon_class'] === $types[2]): ?>
            <div class="post-details__image-wrapper post-photo__image-wrapper">
                <img src="uploads/<?= htmlspecialchars($post['image']) ?>" alt="Фото от пользователя" width="760" height="507">
            </div>

          <?php elseif (isset($_GET) && $post['icon_class'] === $types[0]): ?>
            <div class="post-details__image-wrapper post-quote">
                <div class="post__main">
                    <blockquote>
                        <p>
                            <?= htmlspecialchars($post['content']) ?>
                        </p>
                        <cite>Неизвестный Автор</cite>
                    </blockquote>
                </div>
            </div>

          <?php elseif (isset($_GET) && $post['icon_class'] === $types[1]): ?>
            <div class="post-details__image-wrapper post-text">
              <div class="post__main">
                 <p>
                 <?= htmlspecialchars($post['content']) ?>
                </p>
              </div>
            </div>

          <?php elseif (isset($_GET) && $post['icon_class'] === $types[3]): ?>
            <div class="post__main">
                <div class="post-link__wrapper">
                    <a class="post-link__external" href="http://<?= htmlspecialchars($post['website_link']) ?>" title="Перейти по ссылке">
                    <div class="post-link__info-wrapper">
                        <div class="post-link__icon-wrapper">
                        <img src="https://www.google.com/s2/favicons?domain=<?= htmlspecialchars($post['website_link']) ?>" alt="Иконка">
                        </div>
                        <div class="post-link__info">
                        <h3><?= htmlspecialchars($post['website_link']) ?></h3>
                        </div>
                    </div>
                    </a>
                </div>
            </div>

          <?php elseif (isset($_GET) && $post['icon_class'] === $types[4]): ?>
            <div class="post-details__image-wrapper post-photo__image-wrapper">
                <?= embed_youtube_video(htmlspecialchars($post['video'])) ?>
            </div>
          <?php endif; ?>
          <div class="post__indicators">
            <div class="post__buttons">
              <a class="post__indicator post__indicator--likes button" href="likes.php?post-id=<?= $post['id'] ?>" title="Лайк">
                <svg class="post__indicator-icon" width="20" height="17">
                  <use xlink:href="#icon-heart"></use>
                </svg>
                <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                  <use xlink:href="#icon-heart-active"></use>
                </svg>
                <span><?= htmlspecialchars($post['likes']) ?></span>
                <span class="visually-hidden">количество лайков</span>
              </a>
              <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
                <svg class="post__indicator-icon" width="19" height="17">
                  <use xlink:href="#icon-comment"></use>
                </svg>
                <span><?= htmlspecialchars($commentsCount['count']) ?></span>
                <span class="visually-hidden">количество комментариев</span>
              </a>
              <a class="post__indicator post__indicator--repost button" href="repost.php?post-id=<?= $_GET['post-id']?>" title="Репост">
                <svg class="post__indicator-icon" width="19" height="17">
                  <use xlink:href="#icon-repost"></use>
                </svg>
                <span><?= $repostsCount['count'] ?></span>
                <span class="visually-hidden">количество репостов</span>
              </a>
            </div>
            <span class="post__view"><?= htmlspecialchars($post['views_number']) ?> просмотров</span>
          </div>

          <?php if (!empty($hashtags)): ?>
          <ul class="post__tags">
            <?php foreach ($hashtags as $tag): ?>
                <li><a href="search.php?search=%23<?= $tag ?>">#<?= $tag ?></a></li>
            <?php endforeach; ?>
          </ul>
          <?php endif; ?>
          <div class="comments">
            <form class="comments__form form" action="#" method="post">
              <div class="comments__my-avatar">
                <img class="comments__picture" src="uploads/<?= htmlspecialchars($userAvatar) ?>" alt="Аватар пользователя">
              </div>
              <div class="form__input-section<?= !empty($error) ? ' form__input-section--error' : '' ?>">
                <textarea class="comments__textarea form__textarea form__input" name="comment" placeholder="Ваш комментарий"></textarea>
                <label class="visually-hidden">Ваш комментарий</label>
                <button class="form__error-button button" type="button">!</button>
                <div class="form__error-text">
                  <h3 class="form__error-title">Ошибка валидации</h3>
                  <p class="form__error-desc"><?= $error ?></p>
                </div>
              </div>
              <input class="visually-hidden" id="<?= htmlspecialchars($_GET['post-id'])?>" type="text" name="<?= htmlspecialchars($_GET['post-id']) ?>" value="" placeholder="">
              <button class="comments__submit button button--green" type="submit">Отправить</button>
            </form>
            <div class="comments__list-wrapper">
              <ul class="comments__list">
                <?php foreach ($comments as $comment):?>
                <li class="comments__item user">
                    <div class="comments__avatar">
                        <a class="user__avatar-link" href="#">
                            <img class="comments__picture" src="uploads/<?= !empty($comment['avatar'])  && file_exists('uploads/' . $comment['avatar']) ? htmlspecialchars($comment['avatar']) : 'icon-input-user.svg' ?>" alt="Аватар пользователя">
                        </a>
                    </div>
                    <div class="comments__info">
                        <div class="comments__name-wrapper">
                        <a class="comments__user-name" href="#">
                            <span><?= $comment['login'] ?></span>
                        </a>
                        <time class="comments__time" datetime="<?= htmlspecialchars($comment['creation_date']) ?>"><?= getRelativeFormat(htmlspecialchars($comment['creation_date'])) ?></time>
                        </div>
                        <p class="comments__text">
                        <?= $comment['content'] ?>
                        </p>
                    </div>
                </li>
                <?php endforeach ?>
              </ul>
              <?php if ($commentsCount['count'] > 2 && !isset($_GET['show_all_comments'])): ?>
                <a class="comments__more-link" href="post.php?post-id=<?= $_GET['post-id'] ?>&show_all_comments#last_message">
                    <span>Показать все комментарии</span>
                    <sup class="comments__amount"><?= htmlspecialchars($commentsCount['count']) ?></sup>
                </a>
              <?php elseif ($commentsCount > 2 && isset($_GET['show_all_comments'])): ?>
                <a class="comments__more-link" href="post.php?post-id=<?= htmlspecialchars($_GET['post-id']) ?>">
                    <span>Скрыть комментарии</span>
                    <sup class="comments__amount"><?= htmlspecialchars($commentsCount['count'] - 3) ?></sup>
                    <a name="last_message"></a>
                </a>
              <?php endif ?>
            </div>
          </div>
        </div>
        <div class="post-details__user user">
          <div class="post-details__user-info user__info">
            <div class="post-details__avatar user__avatar">
              <a class="post-details__avatar-link user__avatar-link" href="profile.php?author_id=<?= htmlspecialchars($post['author_id']) ?>">
                <img class="post-details__picture user__picture" src="uploads/<?= !empty($post['avatar']) && file_exists('uploads/' . $post['avatar']) ? htmlspecialchars($post['avatar']) : 'icon-input-user.svg' ?>" alt="Аватар пользователя">
              </a>
            </div>
            <div class="post-details__name-wrapper user__name-wrapper">
              <a class="post-details__name user__name" href="profile.php?author_id=<?= htmlspecialchars($post['author_id']) ?>">
                <span><?= htmlspecialchars($post['login']) ?></span>
              </a>
              <time class="post-details__time user__time" datetime="<?= htmlspecialchars($post['date_creation']) ?>"><?= getRelativeFormat(htmlspecialchars($post['date_creation'])) ?></time>
            </div>
          </div>
          <div class="post-details__rating user__rating">
            <p class="post-details__rating-item user__rating-item user__rating-item--subscribers">
              <span class="post-details__rating-amount user__rating-amount"><?= $followerCounts ?></span>
              <span class="post-details__rating-text user__rating-text">подписчиков</span>
            </p>
            <p class="post-details__rating-item user__rating-item user__rating-item--publications">
              <span class="post-details__rating-amount user__rating-amount"><?= $postsCount ?></span>
              <span class="post-details__rating-text user__rating-text">публикаций</span>
            </p>
          </div>
          <div class="post-details__user-buttons user__buttons">
            <?php if ($followerInformation['follower'] !== $user): ?>
            <a class="user__button user__button--subscription button button--main" href="subscription.php?author_id=<?= htmlspecialchars($post['author_id']) ?>">Подписаться</a>
            <?php  else: ?>
            <a class="user__button user__button--subscription button button--main" href="subscription.php?author_id=<?= htmlspecialchars($post['author_id']) ?>">Отписаться</a>
            <?php endif ?>
            <a class="user__button user__button--writing button button--green" href="<?= htmlspecialchars($post['author_id']) !== $user ? 'messages.php?interlocutor_id=' . htmlspecialchars($post['author_id']) : '' ?>">Сообщение</a>
          </div>
        </div>
      </div>
    </section>
  </div>
</main>
