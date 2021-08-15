CREATE DATABASE readme
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE readme;

CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  registration_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  email VARCHAR(255) NOT NULL UNIQUE,
  login VARCHAR(255) NOT NULL UNIQUE,
  password VARCHAR(255),
  avatar VARCHAR(255)
);

CREATE TABLE content_type (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  content_title VARCHAR(255) NOT NULL,
  icon_class VARCHAR(255) NOT NULL
);

CREATE TABLE hashtags (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  hashtag VARCHAR(255)
);

CREATE TABLE posts (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  title VARCHAR(255) NOT NULL,
  content VARCHAR(255) NOT NULL,
  quote_author VARCHAR(255) DEFAULT NULL,
  image VARCHAR(255) DEFAULT NULL,
  video VARCHAR(255) DEFAULT NULL,
  website_link VARCHAR(255) DEFAULT NULL,
  views_number INT DEFAULT 0,
  author_id INT UNSIGNED,
  type_id INT UNSIGNED,
  hashtag_id INT UNSIGNED,

  FOREIGN KEY (author_id) REFERENCES users (id),
  FOREIGN KEY (type_id) REFERENCES content_type (id),
  FOREIGN KEY (hashtag_id) REFERENCES hashtags (id)
);

CREATE TABLE comments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  creation_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  content VARCHAR(255) NOT NULL,
  author_id INT UNSIGNED,
  post_id INT UNSIGNED,

  FOREIGN KEY (author_id) REFERENCES users (id),
  FOREIGN KEY (post_id) REFERENCES posts (id)
);

CREATE TABLE subscription (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  follower INT UNSIGNED,
  user_id INT UNSIGNED,

  FOREIGN KEY (follower) REFERENCES users (id),
  FOREIGN KEY (user_id) REFERENCES users (id)
);

CREATE TABLE likes (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED,
  liked_post INT UNSIGNED,

  FOREIGN KEY (user_id) REFERENCES users (id),
  FOREIGN KEY (liked_post) REFERENCES posts (id)
);


CREATE TABLE messages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  message_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  content VARCHAR(255),
  sender INT UNSIGNED ,
  recipient INT UNSIGNED,

  FOREIGN KEY (sender) REFERENCES users (id),
  FOREIGN KEY (recipient) REFERENCES users (id)
);

CREATE TABLE posts_hashtags (
  post_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  hashtag_id INT UNSIGNED,

  FOREIGN KEY (post_id) REFERENCES posts (id),
  FOREIGN KEY (hashtag_id) REFERENCES hashtags (id)
);

/*добавляет список типов контента для поста*/
INSERT INTO content_type (content_title, icon_class)
VALUES
('Цитата', 'post-quote'),
('Текст', 'post-text'),
('Фото', 'post-photo'),
('Ссылка', 'post-link');

/*добавляет выдуманных пользователей*/
INSERT INTO users (registration_date, email, login, password, avatar)
VALUES
('2021.01.02 20:17:21', 'danila@mail.ru', 'Лариса', 'dan09', 'userpic-larisa-small.jpg'),
('2021.06.22 05:05:05', 'liza@yandex.ru', 'Владик', 'liza06', 'userpic.jpg'),
('2021.08.13 17:05:43', 'bety@yandex.ru', 'Виктор', 'bety24', 'userpic-mark.jpg');

/*комментарии к разным постам*/
/*INSERT INTO comments (creation_date, content, author_id, post_id)
VALUES
('2021.02.02 22:17:21', 'Прекрасная обработка! Так держать!', 1, 3),
('2021.05.02 12:00:00', 'Просто берет за душу!', 2, 1),
('2021.08.08 17:02:23', 'Лучше курсов не стречал!', 3, 5);*/

/*добавляет существующий список постов*/
INSERT INTO posts (title, type_id, content, views_number, author_id)
VALUES
('Цитата', 1, 'Мы в жизни любим только раз, а после ищем лишь похожих', 200, 1),
('Игра престолов', 2, 'Не могу дождаться начала финального сезона своего любимого сериала!', 3000, 2),
('Наконец, обработал фотки!', 3, 'rock-medium.jpg', 150, 3),
('Моя мечта', 3, 'coast-medium.jpg', 10000, 1),
('Лучшие курсы', 2, 'www.htmlacademy.ru', 400, 2);

/*получает список постов с сортировкой по популярности и вместе с именами авторов и типом контента;*/
SELECT p.title, p.type_id, p.content, p.author_id, p.views_number
FROM posts AS p, users AS u, content_type AS ct
WHERE p.author_id = u.id AND p.type_id = ct.id ORDER BY p.views_number DESC;

/*получает список постов для конкретного пользователя*/
SELECT p.title, p.type_id, p.content, p.author_id, p.views_number
FROM posts AS p
LEFT JOIN users AS u ON p.author_id=u.id
WHERE u.id=1;

/*получает список комментариев для одного поста, в комментариях должен быть логин пользователя*/
SELECT c.*, u.login
FROM comments AS c
LEFT JOIN users AS u ON c.id=u.id
WHERE c.post_id=1;

/*добавляет лайк посту*/
INSERT INTO likes (user_id, liked_post) VALUES (2, 4);

/*подписаться на пользователя*/
INSERT INTO subscription (follower, user_id) VALUES (3, 1);
