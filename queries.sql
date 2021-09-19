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
('2021.01.02 20:17:21', 'danila@mail.ru', 'Лариса', 'dan09', 'userpic-larisa-small.jpg'),
('2021.06.22 05:05:05', 'liza@yandex.ru', 'Владик', 'liza06', 'userpic.jpg'),
('2021.08.13 17:05:43', 'bety@yandex.ru', 'Виктор', 'bety24', 'userpic-mark.jpg');

/*добавляет существующий список постов*/
INSERT INTO posts (title, type_id, content, views_number, author_id)
VALUES
('Цитата', 1, 'Мы в жизни любим только раз, а после ищем лишь похожих', 200, 1),
('Игра престолов', 2, 'Не могу дождаться начала финального сезона своего любимого сериала!', 3000, 2);

INSERT INTO posts (title, type_id, image, views_number, author_id)
VALUES
('Наконец, обработал фотки!', 3, 'uploads/rock-medium.jpg', 150, 3),
('Моя мечта', 3, 'uploads/coast-medium.jpg', 10000, 1);

INSERT INTO posts (title, type_id, website_link, views_number, author_id)
VALUES
('Лучшие курсы', 4, 'www.htmlacademy.ru', 400, 2);

INSERT INTO posts (title, type_id, video, views_number, author_id)
VALUES
('Что такое flexbox', 5, 'https://www.youtube.com/watch?v=8Gu40PFzOHI', 2000, 3);

/*комментарии к разным постам*/
INSERT INTO comments (creation_date, content, author_id, post_id)
VALUES
('2021.02.02 22:17:21', 'Прекрасная обработка! Так держать!', 1, 3),
('2021.05.02 12:00:00', 'Просто берет за душу!', 2, 1),
('2021.08.08 17:02:23', 'Лучше курсов не стречал!', 3, 5);

/*получает список постов с сортировкой по популярности и вместе с именами авторов и типом контента;*/
SELECT p.*, ct.content_title, u.login, u.avatar
FROM posts AS p
JOIN content_type ct ON p.type_id = ct.id
JOIN users u ON p.author_id = u.id
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
INSERT INTO likes (user_id, liked_post) VALUES (2, 4);

/*подписаться на пользователя*/
INSERT INTO subscription (follower, user_id) VALUES (3, 1);
