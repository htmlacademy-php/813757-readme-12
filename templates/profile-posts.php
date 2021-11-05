<section class="profile__posts tabs__content tabs__content--active">
    <h2 class="visually-hidden">Публикации</h2>
    <?php foreach($authorPosts as $authorPost): ?>
    <article class="profile__post post post-<?= htmlspecialchars($authorPost['icon_class']) ?>">
        <?php if ($authorPost['repost']): ?>
        <header class="post__header">
            <div class="post__author">
                <a class="post__author-link" href="#" title="Автор">
                <div class="post__avatar-wrapper post__avatar-wrapper--repost">
                    <img class="post__author-avatar" src="img/userpic-tanya.jpg" alt="Аватар пользователя">
                </div>
                <div class="post__info">
                    <b class="post__author-name">Репост: <?= htmlspecialchars($authorPost['original_recoding_author']) ?></b>
                    <time class="post__time" datetime="<? htmlspecialchars($authorPost['date_creation']) ?>"><?= getRelativeFormat(htmlspecialchars($authorPost['date_creation']))?></time>
                </div>
                </a>
            </div>
        </header>
        <?php else: ?>
        <header class="post__header">
        <h2><a href="#"><?= htmlspecialchars($authorPost['title']) ?></a></h2>
        </header>
        <?php endif ?>
        <div class="post__main">
        <?php if ($authorPost['icon_class'] === $types[0]): ?>
        <blockquote>
            <p>
            <?= htmlspecialchars($authorPost['content']) ?>
            </p>
            <cite>Неизвестный Автор</cite>
        </blockquote>

        <?php elseif ($authorPost['icon_class'] === $types[1]): ?>
            <p>
                <?= htmlspecialchars($authorPost['content']) ?>
            </p>

        <?php elseif ($authorPost['icon_class'] === $types[2]): ?>
        <div class="post-photo__image-wrapper">
            <img  src="uploads/<?= htmlspecialchars($authorPost['image']) ?>" alt="Фото от пользователя" width="760" height="396">
        </div>

        <?php elseif ($authorPost['icon_class'] === $types[3]): ?>
        <div class="post-link__wrapper">
            <a class="post-link__external" href="http://<?= htmlspecialchars($authorPost['website_link']) ?>" title="Перейти по ссылке">
            <div class="post-link__info-wrapper">
                <div class="post-link__icon-wrapper">
                <img src="https://www.google.com/s2/favicons?domain=<?= htmlspecialchars($authorPost['website_link']) ?>" alt="Иконка">
                </div>
                <div class="post-link__info">
                <h3><?= htmlspecialchars($authorPost['website_link']) ?></h3>
                </div>
            </div>
            </a>
        </div>

        <?php elseif ($authorPost['icon_class'] === $types[4]): ?>
            <div class="post-details__image-wrapper post-photo__image-wrapper">
                <?= embed_youtube_video(htmlspecialchars($authorPost['video'])) ?>
            </div>
        <?php endif ?>
        </div>
        <footer class="post__footer">
        <div class="post__indicators">
            <div class="post__buttons">
            <a class="post__indicator post__indicator--likes button" href="likes.php?post-id=<?= htmlspecialchars($authorPost['id']) ?>" title="Лайк">
                <svg class="post__indicator-icon" width="20" height="17">
                <use xlink:href="#icon-heart"></use>
                </svg>
                <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                <use xlink:href="#icon-heart-active"></use>
                </svg>
                <span><?= htmlspecialchars($authorPost['likes']) ?></span>
                <span class="visually-hidden">количество лайков</span>
            </a>
            <a class="post__indicator post__indicator--repost button" href="repost.php?post-id=<?= htmlspecialchars($authorPost['id']) ?>" title="Репост">
                <svg class="post__indicator-icon" width="19" height="17">
                <use xlink:href="#icon-repost"></use>
                </svg>
                <span><?= htmlspecialchars($authorPost['reposts']) ?></span>
                <span class="visually-hidden">количество репостов</span>
            </a>
            </div>
            <time class="post__time" datetime="<?= htmlspecialchars($authorPost['date_creation']) ?>"><?= getRelativeFormat(htmlspecialchars($authorPost['date_creation']))?></time>
        </div>
        <ul class="post__tags">
        <?php foreach($hashtags[$authorPost['id']] as $postHashtag): ?>
            <?php foreach($postHashtag as $hashtag): ?>
            <li><a href="#">#<?= htmlspecialchars($hashtag) ?></a></li>
            <?php endforeach ?>
        <?php endforeach ?>
        </ul>
        </footer>
        <?php if (!isset($_GET['show_comments']) || (isset($_GET['show_comments']) && isset($_GET['post-id']) && $_GET['post-id'] !== $authorPost['id'])): ?>
        <div class="comments">
            <a class="comments__button button" href="profile.php?author_id=<?= $authorPost['author_id'] ?>&post-id=<?= htmlspecialchars($authorPost['id']) ?>&show_comments">Показать комментарии</a>
        </div>
        <?php endif ?>
        <?php if (isset($_GET['show_comments']) && isset($_GET['post-id']) && $_GET['post-id'] === $authorPost['id']):?>
        <div class="comments">
            <div class="comments__list-wrapper">
                <ul class="comments__list">
                <?php foreach ($comments as $comment): ?>
                    <?php if ($comment['post_id'] === $authorPost['id']): ?>
                    <li class="comments__item user">
                    <div class="comments__avatar">
                        <a class="user__avatar-link" href="#">
                        <img class="comments__picture" src="uploads/<?= !empty($comment['avatar']) && file_exists($comment['avatar']) ? htmlspecialchars($comment['avatar']) : "icon-input-user.svg" ?>" alt="Аватар пользователя">
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
                <?php if ($commentsCount > 2 && !isset($_GET['show_all_comments']) && in_array($authorPost['id'], $commentsId)): ?>
                <a class="comments__more-link" href="profile.php?author_id=<?= $authorPost['author_id'] ?>&post-id=<?= $authorPost['id'] ?>&show_comments&show_all_comments"">
                <span>Показать все комментарии</span>
                <sup class="comments__amount"><?= $commentsCount ?></sup>
                </a>
                <?php elseif ($commentsCount > 2 && isset($_GET['show_all_comments']) && in_array($authorPost['id'], $commentsId)): ?>
                <a class="comments__more-link" href="profile.php?author_id=<?= $authorPost['author_id'] ?>">
                <span>Скрыть комментарии</span>
                <sup class="comments__amount"><?= htmlspecialchars($commentsCount) ?></sup>
                </a>
                <?php endif ?>
            </div>
        </div>
        <?php endif ?>
        <?php if (isset($_GET['show_comments']) && isset($_GET['post-id']) && $_GET['post-id'] === $authorPost['id']): ?>
        <form class="comments__form form" action="#" method="post">
        <div class="comments__my-avatar">
            <img class="comments__picture" src="uploads/<?= empty($comment['avatar']) && !file_exists($comment['avatar']) ? $avatar : htmlspecialchars($comment['avatar']) ?>" alt="Аватар пользователя">
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
        <input class="visually-hidden" id="<?= htmlspecialchars($authorPost['id'])?>" type="text" name="<?= htmlspecialchars($authorPost['id']) ?>" value="" placeholder="">
        <button class="comments__submit button button--green" type="submit">Отправить</button>
        </form>
        <?php endif ?>
    </article>
    <?php endforeach ?>
</section>
