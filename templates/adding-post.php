    <main class="page__main page__main--adding-post">
      <div class="page__main-section">
        <div class="container">
          <h1 class="page__title page__title--adding-post">Добавить публикацию</h1>
        </div>
        <div class="adding-post container">
          <div class="adding-post__tabs-wrapper tabs">
            <div class="adding-post__tabs filters">
              <ul class="adding-post__tabs-list filters__list tabs__list">
                <?php foreach($contentTypes as $type): ?>
                    <li class="adding-post__tabs-item filters__item">
                        <a class="adding-post__tabs-link filters__button filters__button--<?= $type['icon_class'] ?> <?= $activeClass = ((isset($_GET['form-type']) && $_GET['form-type'] === $type['icon_class']) || (!isset($_GET['form-type']) && $type['icon_class'] === 'quote')) ? 'filters__button--active' : '' ?> tabs__item tabs__item--active button" href="add.php?form-type=<?= $type['icon_class'] ?>">
                            <svg class="filters__icon" width="22" height="18">
                              <use xlink:href="#icon-filter-<?= $type['icon_class'] ?>"></use>
                            </svg>
                            <span><?= $type['content_title'] ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
              </ul>
            </div>
            <div class="adding-post__tab-content">
              <section class="adding-post__<?= $formType ?> tabs__content tabs__content--active">
                <h2 class="visually-hidden">Форма добавления <?= $formType ?></h2>
                <form class="adding-post__form form" action="add.php?form-type=<?= $formType ?>" method="post" <?= ($formType === $types[2]) ? 'enctype="multipart/form-data"' : "" ?>>
                  <div class="form__text-inputs-wrapper">
                    <div class="form__text-inputs">
                      <div class="adding-post__input-wrapper form__input-wrapper">
                        <label class="adding-post__label form__label" for="heading">Заголовок <span class="form__input-required">*</span></label>
                          <div class="form__input-section <?= isset($errors['heading']) ? 'form__input-section--error' : '' ?>">
                            <input class="adding-post__input form__input" id="heading" value="<?= $_POST['heading'] ?? "" ?>" type="text" name="heading" placeholder="Введите заголовок">
                            <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                          <div class="form__error-text">
                            <h3 class="form__error-title">Заголовок сообщения</h3>
                            <p class="form__error-desc"><?= !empty($errors['heading']) ? $errors['heading'] : '' ?></p>
                          </div>
                        </div>
                      </div>
                      <div class="adding-post__input-wrapper form__input-wrapper">
                        <?php if ($formType === $types[2]): ?>
                            <label class="adding-post__label form__label" for="photo-url">Ссылка из интернета</label>
                            <div class="form__input-section <?= isset($errors['photo-url']) ? 'form__input-section--error' : '' ?>">
                                <input class="adding-post__input form__input" id="photo-url" value="<?= $POST['photo-url'] ?? "" ?>" type="text" name="photo-url" placeholder="Введите ссылку">
                                <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                                <div class="form__error-text">
                                    <h3 class="form__error-title">Заголовок сообщения</h3>
                                    <p class="form__error-desc"><?= !empty($errors['photo-url']) ? $errors['photo-url'] : '' ?></p>
                                </div>
                            </div>
                        <?php elseif ($formType === $types[4]): ?>
                            <label class="adding-post__label form__label" for="video-url">Ссылка youtube <span class="form__input-required">*</span></label>
                            <div class="form__input-section <?= isset($errors['video-url']) ? 'form__input-section--error' : '' ?>">
                                <input class="adding-post__input form__input" id="video-url" value="<?= $_POST['video-url'] ?? "" ?>" type="text" name="video-url" placeholder="Введите ссылку">
                                <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                                <div class="form__error-text">
                                    <h3 class="form__error-title">Заголовок сообщения</h3>
                                    <p class="form__error-desc"><?= !empty($errors['video-url']) ? $errors['video-url'] : '' ?></p>
                                </div>
                                <input class="visually-hidden" id="" type="text" name="video" value="video" title="">
                            </div>

                        <?php elseif ($formType === $types[3]): ?>
                            <label class="adding-post__label form__label" for="post-link">Ссылка <span class="form__input-required">*</span></label>
                            <div class="form__input-section <?= isset($errors['post-link']) ? 'form__input-section--error' : '' ?>">
                                <input class="adding-post__input form__input" id="post-link" value="<?= $_POST['post-link'] ?? "" ?>" type="text" name="post-link">
                                <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                                <div class="form__error-text">
                                    <h3 class="form__error-title">Заголовок сообщения</h3>
                                    <p class="form__error-desc"><?= !empty($errors['post-link']) ? $errors['post-link'] : '' ?></p>
                                </div>
                                <input class="visually-hidden" id="" type="text" name="link" value="link" title="">
                            </div>
                        <?php elseif ($formType === $types[0] || !isset($_GET['form-type']) || empty($_GET['form-type'])): ?>
                            <label class="adding-post__label form__label" for="cite-text">Текст цитаты <span class="form__input-required">*</span></label>
                            <div class="form__input-section  <?= isset($errors['cite-text']) ? 'form__input-section--error' : '' ?>">
                                <textarea class="adding-post__textarea adding-post__textarea--quote form__textarea form__input " id="cite-text" name="cite-text" placeholder="Текст цитаты"><?= $_POST['cite-text'] ?? ""; ?></textarea>
                                <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                                <div class="form__error-text">
                                    <h3 class="form__error-title">Заголовок сообщения</h3>
                                    <p class="form__error-desc"><?= !empty($errors['cite-text']) ? $errors['cite-text'] : '' ?></p>
                                </div>
                            </div>

                        <?php elseif ($formType === $types[1]): ?>
                            <label class="adding-post__label form__label" for="post-text">Текст поста <span class="form__input-required">*</span></label>
                            <div class="form__input-section  <?= isset($errors['post-text']) ? 'form__input-section--error' : '' ?>">
                                <textarea class="adding-post__textarea form__textarea form__input" id="post-text" name="post-text" placeholder="Введите текст публикации"><?= $_POST['post-text'] ?? "" ?></textarea>
                                <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                                <div class="form__error-text">
                                    <h3 class="form__error-title">Заголовок сообщения</h3>
                                    <p class="form__error-desc"><?= !empty($errors['post-text']) ? $errors['post-text'] : '' ?></p>
                                </div>
                                <input class="visually-hidden" id="" type="text" name="text" value="text" title="">
                            </div>
                        <?php endif; ?>
                      </div>
                      <div class="adding-post__input-wrapper form__input-wrapper">
                        <label class="adding-post__label form__label" for="tags">Теги</label>
                        <div class="form__input-section <?= isset($errors['tags']) ? 'form__input-section--error' : '' ?>">
                          <input class="adding-post__input form__input" id="tags" value="<?= $_POST['tags'] ?? "" ?>" type="text" name="tags" placeholder="Введите теги">
                          <button class="form__error-button button" type="button">!<span class="visually-hidden">Информация об ошибке</span></button>
                          <div class="form__error-text">
                            <h3 class="form__error-title">Заголовок сообщения</h3>
                            <p class="form__error-desc"><?= !empty($errors['tags']) ? $errors['tags'] : '' ?></p>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php if (!empty($errors)):?>
                    <div class="form__invalid-block">
                      <b class="form__invalid-slogan">Пожалуйста, исправьте следующие ошибки:</b>
                      <ul class="form__invalid-list">
                        <?php foreach($errors as $key => $error): ?>
                          <li class="form__invalid-item"><?= $russianTranslation[$key].': '.$error ?></li>
                        <?php endforeach; ?>
                      </ul>
                    </div>
                    <?php endif; ?>
                  </div>
                  <?php if ($formType === $types[2]): ?>
                    <div class="adding-post__input-file-container form__input-container form__input-container--file">
                        <div class="adding-post__input-file-wrapper form__input-file-wrapper">
                            <div class="adding-post__file-zone adding-post__file-zone--photo form__file-zone dropzone">
                                <input class="adding-post__input-file form__input-file" id="userpic-file-photo" type="file" name="userpic-file-photo" title="">
                                <div class="form__file-zone-text">
                                    <span>Выберите фото</span>
                                </div>
                            </div>
                        </div>
                        <div class="adding-post__file adding-post__file--photo form__file dropzone-previews">
                        </div>
                    </div>
                  <?php endif; ?>
                  <input class="visually-hidden" id="" type="text" name="" value="" title="">
                  <div class="adding-post__buttons">
                    <button class="adding-post__submit button button--main" type="submit">Опубликовать</button>
                    <a class="adding-post__close" href="popular.php">Закрыть</a>
                  </div>
                </form>
              </section>
            </div>
          </div>
        </div>
      </div>
    </main>

    <div class="modal modal--adding">
      <div class="modal__wrapper">
        <button class="modal__close-button button" type="button">
          <svg class="modal__close-icon" width="18" height="18">
            <use xlink:href="#icon-close"></use>
          </svg>
          <span class="visually-hidden">Закрыть модальное окно</span></button>
        <div class="modal__content">
          <h1 class="modal__title">Пост добавлен</h1>
          <p class="modal__desc">
            Озеро Байкал – огромное древнее озеро в горах Сибири к северу от монгольской границы. Байкал считается самым глубоким озером в мире. Он окружен сефтью пешеходных маршрутов, называемых Большой байкальской тропой. Деревня Листвянка, расположенная на западном берегу озера, – популярная отправная точка для летних экскурсий.
          </p>
          <div class="modal__buttons">
            <a class="modal__button button button--main" href="#">Синяя кнопка</a>
            <a class="modal__button button button--gray" href="#">Серая кнопка</a>
          </div>
        </div>
      </div>
    </div>
