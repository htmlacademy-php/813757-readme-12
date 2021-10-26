<main class="page__main page__main--profile">
  <h1 class="visually-hidden">Профиль</h1>
  <div class="profile profile--posts">
    <div class="profile__user-wrapper">
      <div class="profile__user user container">
        <div class="profile__user-info user__info">
          <div class="profile__avatar user__avatar">
            <img class="profile__picture user__picture" src="uploads/<?= htmlspecialchars(!empty($authorData['avatar']) ? $authorData['avatar'] : "icon-input-user.svg") ?>" alt="Аватар пользователя">
          </div>
          <div class="profile__name-wrapper user__name-wrapper">
            <span class="profile__name user__name"><?= $authorData['login'] ?></span>
            <time class="profile__user-time user__time" datetime="<?= $authorData['registration_date'] ?>"><?= getRelativeFormat($authorData['registration_date'], "на сайте") ?></time>
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
          <a class="profile__user-button user__button user__button--subscription button button--main" href="subscription.php?author_id=<?= $_GET['author_id'] ?>">Подписаться</a>
          <?php  else: ?>
          <a class="profile__user-button user__button user__button--subscription button button--main" href="subscription.php?author_id=<?= $_GET['author_id'] ?>">Отписаться</a>
          <?php endif ?>
          <a class="profile__user-button user__button user__button--writing button button--green" href="#">Сообщение</a>
        </div>
      </div>
    </div>
    <div class="profile__tabs-wrapper tabs">
      <div class="container">
        <div class="profile__tabs filters">
          <b class="profile__tabs-caption filters__caption">Показать:</b>
          <ul class="profile__tabs-list filters__list tabs__list">
            <li class="profile__tabs-item filters__item">
              <a class="profile__tabs-link filters__button filters__button--active tabs__item tabs__item--active button">Посты</a>
            </li>
            <li class="profile__tabs-item filters__item">
              <a class="profile__tabs-link filters__button tabs__item button" href="#">Лайки</a>
            </li>
            <li class="profile__tabs-item filters__item">
              <a class="profile__tabs-link filters__button tabs__item button" href="#">Подписки</a>
            </li>
          </ul>
        </div>
        <div class="profile__tab-content">
          <!--здесь надо будет менять контент!!!-->
          <section class="profile__posts tabs__content tabs__content--active">
            <h2 class="visually-hidden">Публикации</h2>
            <?php foreach($autorPosts as $autorPost): ?>
            <article class="profile__post post post-<?= htmlspecialchars($autorPost['icon_class']) ?>">
              <?php if ($autorPost['repost']): ?>
                <header class="post__header">
                    <div class="post__author">
                        <a class="post__author-link" href="#" title="Автор">
                        <div class="post__avatar-wrapper post__avatar-wrapper--repost">
                            <img class="post__author-avatar" src="img/userpic-tanya.jpg" alt="Аватар пользователя">
                        </div>
                        <div class="post__info">
                            <b class="post__author-name">Репост: <?= htmlspecialchars($autorPost['original_recoding_author']) ?></b>
                            <time class="post__time" datetime="<? htmlspecialchars($autorPost['date_creation']) ?>"><?= getRelativeFormat(htmlspecialchars($autorPost['date_creation']))?></time>
                        </div>
                        </a>
                    </div>
                </header>
              <?php else: ?>
              <header class="post__header">
                <h2><a href="#"><?= htmlspecialchars($autorPost['title']) ?></a></h2>
              </header>
              <?php endif ?>
              <div class="post__main">
                <?php if ($autorPost['icon_class'] === $types[0]): ?>
                <blockquote>
                  <p>
                    <?= htmlspecialchars($autorPost['content']) ?>
                  </p>
                  <cite>Неизвестный Автор</cite>
                </blockquote>

                <?php elseif ($autorPost['icon_class'] === $types[1]): ?>
                    <p>
                        <?= htmlspecialchars($autorPost['content']) ?>
                    </p>

                <?php elseif ($autorPost['icon_class'] === $types[2]): ?>
                <div class="post-photo__image-wrapper">
                    <img src="uploads/<?= htmlspecialchars($autorPost['image']) ?>" alt="Фото от пользователя" width="760" height="507">
                </div>

                <?php elseif ($autorPost['icon_class'] === $types[3]): ?>
                <div class="post-link__wrapper">
                    <a class="post-link__external" href="http://<?= htmlspecialchars($autorPost['website_link']) ?>" title="Перейти по ссылке">
                    <div class="post-link__info-wrapper">
                        <div class="post-link__icon-wrapper">
                        <img src="https://www.google.com/s2/favicons?domain=<?= htmlspecialchars($autorPost['website_link']) ?>" alt="Иконка">
                        </div>
                        <div class="post-link__info">
                        <h3><?= htmlspecialchars($autorPost['website_link']) ?></h3>
                        </div>
                    </div>
                    </a>
                </div>

                <?php elseif ($autorPost['icon_class'] === $types[4]): ?>
                    <div class="post-details__image-wrapper post-photo__image-wrapper">
                        <?= embed_youtube_video(htmlspecialchars($autorPost['video'])) ?>
                    </div>
              <?php endif ?>
              </div>
              <footer class="post__footer">
                <div class="post__indicators">
                  <div class="post__buttons">
                    <a class="post__indicator post__indicator--likes button" href="likes.php?post-id=<?= $autorPost['id'] ?>" title="Лайк">
                      <svg class="post__indicator-icon" width="20" height="17">
                        <use xlink:href="#icon-heart"></use>
                      </svg>
                      <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                        <use xlink:href="#icon-heart-active"></use>
                      </svg>
                      <span><?= htmlspecialchars($autorPost['likes']) ?></span>
                      <span class="visually-hidden">количество лайков</span>
                    </a>
                    <a class="post__indicator post__indicator--repost button" href="#" title="Репост">
                      <svg class="post__indicator-icon" width="19" height="17">
                        <use xlink:href="#icon-repost"></use>
                      </svg>
                      <span><?= htmlspecialchars($autorPost['reposts']) ?></span>
                      <span class="visually-hidden">количество репостов</span>
                    </a>
                  </div>
                  <time class="post__time" datetime="<?= htmlspecialchars($autorPost['date_creation']) ?>"><?= getRelativeFormat(htmlspecialchars($autorPost['date_creation']))?></time>
                </div>
                <ul class="post__tags">
                <?php foreach($hashtags[$autorPost['id']] as $postHashtag): ?>
                    <?php foreach($postHashtag as $hashtag): ?>
                    <li><a href="#">#<?= htmlspecialchars($hashtag) ?></a></li>
                    <?php endforeach ?>
                <?php endforeach ?>
                </ul>
              </footer>
              <?php if (!isset($_GET['show_comments'])): ?>
              <div class="comments">
                <a class="comments__button button" href="profile.php?author_id=<?= $autorPost['author_id'] ?>&show_comments ?>">Показать комментарии</a>
              </div>
              <?php endif ?>
              <?php if (isset($_GET['show_comments'])):?>
              <div class="comments">
                <div class="comments__list-wrapper">
                  <ul class="comments__list">
                    <?php foreach ($comments as $comment): ?>
                    <?php if ($comment['post_id'] === $autorPost['id']): ?>
                    <li class="comments__item user">
                      <div class="comments__avatar">
                        <a class="user__avatar-link" href="#">
                          <img class="comments__picture" src="uploads/<?= htmlspecialchars(!empty($comment['avatar']) ? $comment['avatar'] : "icon-input-user.svg") ?>" alt="Аватар пользователя">
                        </a>
                      </div>
                      <div class="comments__info">
                        <div class="comments__name-wrapper">
                          <a class="comments__user-name" href="#">
                            <span><?= htmlspecialchars($comment['login']) ?></span>
                          </a>
                          <time class="comments__time" datetime="<?= htmlspecialchars($comment['creation_date']) ?>"><?= getRelativeFormat(htmlspecialchars($comment['creation_date']))?></time>
                        </div>
                        <p class="comments__text">
                        <?= htmlspecialchars($comment['content']) ?>
                        </p>
                      </div>
                    </li>
                    <?php endif ?>
                    <?php endforeach ?>
                  </ul>
                  <?php if ($commentsCount > 2 && !isset($_GET['show_all_comments']) && in_array($autorPost['id'], $commentsId)): ?>
                  <a class="comments__more-link" href="profile.php?author_id=<?= $autorPost['author_id'] ?>&show_comments&show_all_comments"">
                    <span>Показать все комментарии</span>
                    <sup class="comments__amount"><?= $commentsCount ?></sup>
                  </a>
                  <?php elseif ($commentsCount > 2 && isset($_GET['show_all_comments']) && in_array($autorPost['id'], $commentsId)): ?>
                  <a class="comments__more-link" href="profile.php?author_id=<?= $autorPost['author_id'] ?>">
                    <span>Скрыть комментарии</span>
                    <sup class="comments__amount"><?= htmlspecialchars($commentsCount - 3) ?></sup>
                  </a>
                  <?php endif ?>
                </div>
              </div>
              <?php endif ?>
              <?php if (isset($_GET['show_comments'])): ?>
              <form class="comments__form form" action="#" method="post">
                <div class="comments__my-avatar">
                    <img class="comments__picture" src="uploads/<?= empty($comment['avatar']) ? $avatar : $comment['avatar'] ?>" alt="Аватар пользователя">
                </div>
                <div class="form__input-section <?= !empty($error) ? 'form__input-section--error' : '' ?>">
                    <textarea class="comments__textarea form__textarea form__input" name="comment" placeholder="Ваш комментарий"></textarea>
                    <label class="visually-hidden">Ваш комментарий</label>
                    <button class="form__error-button button" type="button">!</button>
                    <div class="form__error-text">
                    <h3 class="form__error-title">Ошибка валидации</h3>
                    <p class="form__error-desc"><?= $error ?></p>
                    </div>
                </div>
                <input class="visually-hidden" id="<?= htmlspecialchars($autorPost['id'])?>" type="text" name="<?= htmlspecialchars($autorPost['id']) ?>" value="" placeholder="">
                <button class="comments__submit button button--green" type="submit">Отправить</button>
              </form>
              <?php endif ?>
            </article>
            <?php endforeach ?>
          </section>
          <!--до этого места контент должен меняться в зависимости от активного таба!!!-->
        </div>
      </div>
    </div>
  </div>
</main>
