SET NAMES utf8;

/*добавляет список типов контента для поста*/
INSERT INTO content_type (content_title, icon_class)
VALUES
('Цитата', 'quote'),
('Текст', 'text'),
('Фото', 'photo'),
('Ссылка', 'link'),
('Видео', 'video');

/*добавляет выдуманных пользователей*/
INSERT INTO users (registration_date, email, login, password, avatar)
VALUES
('2021.01.02 20:17:21', 'danila@mail.ru', 'Лариса', '12345678910', 'userpic-larisa.jpg'),
('2021.06.22 05:05:05', 'liza@yandex.ru', 'Владик', 'liza06', 'userpic.jpg'),
('2021.08.13 17:05:43', 'bety@yandex.ru', 'Виктор', 'bety24', 'userpic-mark.jpg');

/*добавляет существующий список постов*/
INSERT INTO posts (date_creation, title, type_id, content, views_number, author_id)
VALUES
('2021.01.02 20:17:21', 'Цитата', 1, 'Мы в жизни любим только раз, а после ищем лишь похожих', 200, 1),
('2021.06.02 20:17:21', 'Игра престолов', 2, 'Не могу дождаться начала финального сезона своего любимого сериала!', 3000, 2);

INSERT INTO posts (date_creation, title, type_id, image, views_number, author_id)
VALUES
('2021.10.21 20:17:21', 'Наконец, обработал фотки!', 3, 'rock-medium.jpg', 150, 3),
('2021.10.14 20:17:21', 'Моя мечта', 3, 'coast-medium.jpg', 10000, 1);

INSERT INTO posts (date_creation, title, type_id, website_link, views_number, author_id)
VALUES
('2021.10.10 20:17:21', 'Лучшие курсы', 4, 'www.htmlacademy.ru', 400, 2);

INSERT INTO posts (date_creation, title, type_id, video, views_number, author_id)
VALUES
('2021.10.18 20:17:21', 'Что такое flexbox', 5, 'https://www.youtube.com/watch?v=8Gu40PFzOHI', 2000, 3);

/*комментарии к разным постам*/
INSERT INTO comments (creation_date, content, author_id, post_id)
VALUES
('2021.02.02 22:17:21', 'Прекрасная обработка! Так держать!', 1, 3),
('2021.05.02 12:00:00', 'Просто берет за душу!', 2, 1),
('2021.08.08 17:02:23', 'Лучше курсов не стречал!', 3, 5);

/*добавляет тэги*/
INSERT INTO hashtags (hashtag) VALUES ('nature'), ('globe'), ('photooftheday'), ('canon'), ('landscape'), ('щикарныйвид');

/*добавляет теги к постам*/
INSERT INTO posts_hashtags (post_id, hashtag_id) VALUES (1, 1), (1, 2), (1, 3), (1, 4), (1,5), (1, 6), (2, 1), (2, 2), (2, 3), (3, 4), (3,5), (3, 6), (4, 1), (4, 2), (4, 3), (4, 4), (4,5), (5, 6), (6, 6);

/*получает список постов с сортировкой по популярности и вместе с именами авторов и типом контента и хэштегами*/
SELECT p.*, ct.content_title, u.login, u.avatar, ph.post_id, ph.hashtag_id
FROM posts AS P
JOIN content_type ct ON p.type_id = ct.id
JOIN users u ON p.author_id = u.id
JOIN posts_hashtags ph ON ph.post_id = p.id
JOIN hashtags h ON h.id = ph.hashtag_id
ORDER BY p.views_number DESC;

/*получает список постов для конкретного пользователя*/
SELECT p.title, p.content, u.login, p.views_number
FROM posts AS p
LEFT JOIN users AS u ON p.author_id=u.id
WHERE u.id=1;

/*получает список комментариев для одного поста, в комментариях должен быть логин пользователя*/
SELECT c.creation_date, c.content, u.login
FROM comments AS c
LEFT JOIN users AS u ON c.id=u.id
WHERE c.post_id=1;

/*добавляет лайк посту*/
INSERT INTO likes (user_id, liked_post) VALUES (2, 4), (1, 2), (3, 1), (2, 3), (3, 4), (1, 4), (3, 2), (1, 5), (3, 5), (2, 5), (3, 3);

/*подписаться на пользователя*/
INSERT INTO subscription (follower, user_id) VALUES (3, 1);

INSERT INTO comments (creation_date, content, author_id, post_id)
VALUES
('2021.08.08 17:02:23', 'Красота!!!1!', 1, 4),
('2021.10.22 13:02:23', 'Озеро Байкал – огромное древнее озеро в горах Сибири к северу от монгольской границы. Байкал считается самым глубоким озером в мире. Он окружен сетью пешеходных маршрутов, называемых Большой байкальской тропой. Деревня Листвянка, расположенная на западном берегу озера, – популярная отправная точка для летних экскурсий. Зимой здесь можно кататься на коньках и собачьих упряжках.', 1, 4);
