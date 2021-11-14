CREATE DATABASE IF NOT EXISTS readme
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
  content TEXT(1000) DEFAULT NULL,
  quote_author VARCHAR(255) DEFAULT NULL,
  image TEXT DEFAULT NULL,
  video VARCHAR(255) DEFAULT NULL,
  website_link VARCHAR(255) DEFAULT NULL,
  views_number INT DEFAULT 0,
  author_id INT UNSIGNED,
  type_id INT UNSIGNED,
  original_recoding_author INT UNSIGNED,
  original_id INT UNSIGNED,
  repost BOOLEAN DEFAULT NULL,

  FOREIGN KEY (author_id) REFERENCES users (id),
  FOREIGN KEY (type_id) REFERENCES content_type (id),
  FOREIGN KEY (original_recoding_author) REFERENCES users (id),
  FOREIGN KEY (original_id) REFERENCES posts (id)
);

CREATE TABLE comments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  creation_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  content VARCHAR(2000) NOT NULL,
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
  flag INT UNSIGNED,

  FOREIGN KEY (sender) REFERENCES users (id),
  FOREIGN KEY (recipient) REFERENCES users (id)
);

CREATE TABLE posts_hashtags (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  post_id INT UNSIGNED,
  hashtag_id INT UNSIGNED,

  FOREIGN KEY (post_id) REFERENCES posts (id),
  FOREIGN KEY (hashtag_id) REFERENCES hashtags (id)
);

CREATE FULLTEXT INDEX text_search ON posts(title, content);
