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
                        <a class="sorting__link sorting__link--active" href="?<?= isset($_GET['type_id']) ? 'type_id='.$_GET['type_id'] : '' ?>&sort=views_number&order=<?= $order === 'desc' ? 'asc' : 'desc' ?>">
                            <span>Популярность</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a class="sorting__link" href="#">
                            <span>Лайки</span>
                            <svg class="sorting__icon" width="10" height="12">
                                <use xlink:href="#icon-sort"></use>
                            </svg>
                        </a>
                    </li>
                    <li class="sorting__item">
                        <a class="sorting__link" href="#">
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
            <?php foreach ($cardsInformation as $key => $cardInformation): ?>
                <article class="popular__post post post-<?= $cardInformation['icon_class'] ?>">
                    <header class="post__header">
                        <h2>
                            <a href="post.php?post-id=<?= $cardInformation['id']; ?>">
                                <?= htmlspecialchars($cardInformation['title']) ?>
                            </a>
                        </h2>
                    </header>
                    <div class="post__main">
                        <?php if ($cardInformation['icon_class'] === $types[0]): ?>
                        <blockquote>
                            <p>
                                <?= htmlspecialchars($cardInformation['content']) ?>
                            </p>
                            <cite>Неизвестный Автор</cite>
                        </blockquote>

                        <?php elseif ($cardInformation['icon_class'] === $types[1]): ?>
                            <p><?= getCutString(htmlspecialchars($cardInformation['content'])) ?></p>

                        <?php elseif ($cardInformation['icon_class'] === $types[2]): ?>
                            <div class="post-photo__image-wrapper">
                                <img src="uploads/<?= htmlspecialchars($cardInformation['image']) ?>" alt="Фото от пользователя" width="360" height="240">
                            </div>

                        <?php elseif ($cardInformation['icon_class'] === $types[3]): ?>
                            <div class="post-link__wrapper">
                                <a class="post-link__external" href="http://" title="Перейти по ссылке">
                                    <div class="post-link__info-wrapper">
                                        <div class="post-link__icon-wrapper">
                                            <img src="https://www.google.com/s2/favicons?domain=vitadental.ru" alt="Иконка">
                                        </div>
                                        <div class="post-link__info">
                                            <h3><?= htmlspecialchars($cardInformation['title']) ?></h3>
                                        </div>
                                    </div>
                                    <span><?= htmlspecialchars($cardInformation['website_link']) ?></span>
                                </a>
                            </div>

                        <?php elseif ($cardInformation['icon_class'] === $types[4]): ?>
                            <div class="post-video__block">
                                <div class="post-video__preview">
                                    <?= embed_youtube_cover($cardInformation['video']) ?>
                                </div>
                                <a href="post-details.html" class="post-video__play-big button">
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
                            <a class="post__author-link" href="#" title="Автор">
                                <div class="post__avatar-wrapper">
                                    <img class="post__author-avatar" src="img/<?= htmlspecialchars($cardInformation['avatar']) ?>" alt="Аватар пользователя">
                                </div>
                                <div class="post__info">
                                    <b class="post__author-name"><?= htmlspecialchars($cardInformation['login']) ?></b>
                                    <time class="post__time" datetime="<?= getPublicationTime($key) ?>" title="<?= getFormatTime($key); ?>"><?= getRelativeFormat($key); ?></time>
                                </div>
                            </a>
                        </div>
                        <div class="post__indicators">
                            <div class="post__buttons">
                                <a class="post__indicator post__indicator--likes button" href="#" title="Лайк">
                                    <svg class="post__indicator-icon" width="20" height="17">
                                        <use xlink:href="#icon-heart"></use>
                                    </svg>
                                    <svg class="post__indicator-icon post__indicator-icon--like-active" width="20" height="17">
                                        <use xlink:href="#icon-heart-active"></use>
                                    </svg>
                                    <span><?= htmlspecialchars($cardInformation['views_number']) ?></span>
                                    <span class="visually-hidden">количество лайков</span>
                                </a>
                                <a class="post__indicator post__indicator--comments button" href="#" title="Комментарии">
                                    <svg class="post__indicator-icon" width="19" height="17">
                                        <use xlink:href="#icon-comment"></use>
                                    </svg>
                                    <span>0</span>
                                    <span class="visually-hidden">количество комментариев</span>
                                </a>
                            </div>
                        </div>
                    </footer>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
