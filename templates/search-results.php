<main class="page__main page__main--search-results">
  <h1 class="visually-hidden">Страница результатов поиска</h1>
  <section class="search">
    <h2 class="visually-hidden">Результаты поиска</h2>
    <div class="search__query-wrapper">
      <div class="search__query container">
        <span>Вы искали:</span>
        <span class="search__query-text"><?= htmlspecialchars($_GET['search']) ?></span>
      </div>
    </div>
    <div class="search__results-wrapper">
      <div class="container">
        <div class="search__content">
          <?php foreach ($posts as $post): ?>
            <article class="search__post post post-<?= $post['icon_class'] ?>">
                <header class="post__header post__author">
                    <a class="post__author-link" href="#" title="Автор">
                        <div class="post__avatar-wrapper">
                            <img class="post__author-avatar" src="uploads/<?= !empty($post['avatar']) && file_exists('uploads/' . $post['avatar']) ? htmlspecialchars($post['avatar']) : 'icon-input-user.svg' ?>" alt="Аватар пользователя" width="60" height="60">
                        </div>
                        <div class="post__info">
                            <b class="post__author-name"><?= $post['login'] ?></b>
                            <span class="post__time">15 минут назад</span>
                        </div>
                    </a>
                </header>
                <div class="post__main">
                    <?php if (isset($_GET) && $post['icon_class'] === $types[2]): ?>
                    <h2><a href="#"><?= $post['title'] ?></a></h2>
                    <div class="post-photo__image-wrapper">
                        <img src="uploads/<?= $post['image'] ?>" alt="Фото от пользователя" width="760" height="396">
                    </div>

                    <?php elseif (isset($_GET) && $post['icon_class'] === $types[1]): ?>
                    <h2><a href="#"><?= $post['title'] ?></a></h2>
                    <p><?= $post['content'] ?></p>
                    <a class="post-text__more-link" href="#">Читать далее</a>

                    <?php elseif (isset($_GET) && $post['icon_class'] === $types[4]): ?>
                    <div class="post-video__block">
                        <div class="post-video__preview">
                            <?= embed_youtube_cover($post['video'], 760, 396) ?>
                        </div>
                        <div class="post-video__control">
                            <button class="post-video__play post-video__play--paused button button--video" type="button"><span class="visually-hidden">Запустить видео</span></button>
                            <div class="post-video__scale-wrapper">
                                <div class="post-video__scale">
                                    <div class="post-video__bar">
                                        <div class="post-video__toggle"></div>
                                    </div>
                                </div>
                            </div>
                            <button class="post-video__fullscreen post-video__fullscreen--inactive button button--video" type="button"><span class="visually-hidden">Полноэкранный режим</span></button>
                        </div>
                        <button class="post-video__play-big button" type="button">
                            <svg class="post-video__play-big-icon" width="27" height="28">
                                <use xlink:href="#icon-video-play-big"></use>
                            </svg>
                            <span class="visually-hidden">Запустить проигрыватель</span>
                        </button>
                    </div>

                    <?php elseif (isset($_GET) && $post['icon_class'] === $types[0]): ?>
                    <blockquote>
                        <p><?= $post['content'] ?></p>
                        <cite><?= $post['quote_author'] ?></cite>
                    </blockquote>

                    <?php elseif (isset($_GET) && $post['icon_class'] === $types[3]): ?>
                        <div class="post-link__wrapper">
                            <a class="post-link__external" href="<?= $post['website_link'] ?>" title="Перейти по ссылке">
                            <div class="post-link__icon-wrapper">
                                <img src="img/logo-vita.jpg" alt="Иконка">
                            </div>
                            <div class="post-link__info">
                                <h3><?= $post['title'] ?></h3>
                                <span><?= $post['website_link'] ?></span>
                            </div>
                            <svg class="post-link__arrow" width="11" height="16">
                                <use xlink:href="#icon-arrow-right-ad"></use>
                            </svg>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                <footer class="post__footer post__indicators">
                    <div class="post__buttons">
                        <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">
                        <svg class="post__indicator-icon" width="20" height="17">
                            <use xlink:href="#icon-heart"></use>
                        </svg>
                        <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                            <use xlink:href="#icon-heart-active"></use>
                        </svg>
                        <span>250</span>
                        <span class="visually-hidden">количество лайков</span>
                        </a>
                        <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
                        <svg class="post__indicator-icon" width="19" height="17">
                            <use xlink:href="#icon-comment"></use>
                        </svg>
                        <span>25</span>
                        <span class="visually-hidden">количество комментариев</span>
                        </a>
                    </div>
                </footer>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>
</main>
