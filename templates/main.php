<section class="page__main page__main--popular">
    <div class="container">
        <h1 class="page__title page__title--popular">Популярное</h1>
    </div>
    <div class="popular container">
        <div class="popular__filters-wrapper">
            <div class="popular__sorting sorting">
                <b class="popular__sorting-caption sorting__caption">Сортировка:</b>
                <ul class="popular__sorting-list sorting__list">
                    <li class="sorting__item sorting__item--popular">
                        <a class="sorting__link<?= ($sort === 'views_number' || !isset($_GET['sort'])) ? ' sorting__link--active' : '' ?>" href="?<?= isset($_GET['type_id']) ? 'type_id='.$_GET['type_id'].'&' : '' ?>sort=views_number&order=<?= $order === 'asc' ? 'desc' : 'asc' ?>">
                            <span>Популярность</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a class="sorting__link<?= $sort === 'likes' ? ' sorting__link--active' : '' ?>" href="?<?= isset($_GET['type_id']) ? 'type_id='.$_GET['type_id'].'&' : '' ?>sort=likes&order=<?= $order === 'asc' ? 'desc' : 'asc' ?>">
                            <span>Лайки</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a class="sorting__link<?= $sort === 'date_creation' ? ' sorting__link--active' : '' ?>" href="?<?= isset($_GET['type_id']) ? 'type_id='.$_GET['type_id'].'&' : '' ?>sort=date_creation&order=<?= $order === 'asc' ? 'desc' : 'asc' ?>">
                            <span>Дата</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="popular__filters filters">
                <b class="popular__filters-caption filters__caption">Тип контента:</b>
                <ul class="popular__filters-list filters__list">
                    <li class="popular__filters-item popular__filters-item--all filters__item filters__item--all">
                        <a class="filters__button filters__button--ellipse filters__button--all <?= $activeClass = !isset($_GET['type_id']) ? 'filters__button--active' : ''; ?>" href="popular.php">
                            <span>Все</span>
                        </a>
                    </li>

                    <?php foreach ($contentTypes as $type): ?>
                        <li class="popular__filters-item filters__item">
                            <a class="filters__button filters__button--<?= $type['icon_class']; ?> button <?= $activeClass = isset($_GET['type_id']) && $_GET['type_id'] === $type['id'] ? 'filters__button--active' : ''; ?>" href="popular.php?type_id=<?= $type['id']; ?>">
                                <span class="visually-hidden">Фото</span>
                                <svg class="filters__icon" width="22" height="18">
                                    <use xlink:href="#icon-filter-<?= $type['icon_class']; ?>"></use>
                                </svg>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="popular__posts">
            <?php foreach ($posts as $key => $post): ?>
                <article class="popular__post post post-<?= htmlspecialchars($post['icon_class']) ?>">
                    <header class="post__header">
                        <h2>
                            <a href="post.php?post-id=<?= htmlspecialchars($post['id']) ?>">
                                <?= htmlspecialchars($post['title']) ?>
                            </a>
                        </h2>
                    </header>
                    <div class="post__main">
                        <?php if ($post['icon_class'] === $types[0]): ?>
                        <blockquote>
                            <p>
                                <?= htmlspecialchars($post['content']) ?>
                            </p>
                            <cite>Неизвестный Автор</cite>
                        </blockquote>

                        <?php elseif ($post['icon_class'] === $types[1]): ?>
                            <p><?= getCutString(htmlspecialchars($post['content'])) ?></p>

                        <?php elseif ($post['icon_class'] === $types[2]): ?>
                            <div class="post-photo__image-wrapper">
                                <img src="uploads/<?= htmlspecialchars($post['image']) ?>" alt="Фото от пользователя" width="360" height="240">
                            </div>

                        <?php elseif ($post['icon_class'] === $types[3]): ?>
                            <div class="post-link__wrapper">
                                <a class="post-link__external" href="http://" title="Перейти по ссылке">
                                    <div class="post-link__info-wrapper">
                                        <div class="post-link__icon-wrapper">
                                            <img src="https://www.google.com/s2/favicons?domain=vitadental.ru" alt="Иконка">
                                        </div>
                                        <div class="post-link__info">
                                            <h3><?= htmlspecialchars($post['title']) ?></h3>
                                        </div>
                                    </div>
                                    <span><?= htmlspecialchars($post['website_link']) ?></span>
                                </a>
                            </div>

                        <?php elseif ($post['icon_class'] === $types[4]): ?>
                            <div class="post-video__block">
                                <div class="post-video__preview">
                                    <?= embed_youtube_cover(htmlspecialchars($post['video'])) ?>
                                </div>
                                <a href="post.php?post-id=<?= htmlspecialchars($post['id']) ?>" class="post-video__play-big button">
                                    <svg class="post-video__play-big-icon" width="14" height="14">
                                        <use xlink:href="#icon-video-play-big"></use>
                                    </svg>
                                    <span class="visually-hidden">Запустить проигрыватель</span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <footer class="post__footer">
                        <div class="post__author">
                            <a class="post__author-link" href="profile.php?author_id=<?= htmlspecialchars($post['author_id']) ?>" title="Автор">
                                <div class="post__avatar-wrapper">
                                    <img class="post__author-avatar" src="uploads/<?= htmlspecialchars(!empty($post['avatar']) ? $post['avatar'] : "icon-input-user.svg") ?>" alt="Аватар пользователя">
                                </div>
                                <div class="post__info">
                                    <b class="post__author-name"><?= htmlspecialchars($post['login']) ?></b>
                                    <time class="post__time" datetime="<?= $post['date_creation'] ?>" title="<?= $post['date_creation'] ?>"><?= getRelativeFormat($post['date_creation']) ?></time>
                                </div>
                            </a>
                        </div>
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
                                <a class="post__indicator post__indicator--comments button" href="post.php?post-id=<?= htmlspecialchars($post['id']) ?>" title="Комментарии">
                                    <svg class="post__indicator-icon" width="19" height="17">
                                        <use xlink:href="#icon-comment"></use>
                                    </svg>
                                    <span><?= htmlspecialchars($post['comments']) ?></span>
                                    <span class="visually-hidden">количество комментариев</span>
                                </a>
                            </div>
                        </div>
                    </footer>
                </article>
            <?php endforeach; ?>
        </div>
        <?php if ($postCount > 9): ?>
        <div class="popular__page-links">
            <a class="popular__page-link popular__page-link--prev button button--gray" href="?page=<?= $previous ?>">Предыдущая страница</a>
            <a class="popular__page-link popular__page-link--next button button--gray" href="?page=<?= $next ?>">Следующая страница</a>
        </div>
        <?php endif ?>
    </div>
</section>
