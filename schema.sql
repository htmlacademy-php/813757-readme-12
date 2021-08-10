CREATE DATABASE readme
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE readme;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  registration_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  email VARCHAR(255) UNIQUE,
  user_login VARCHAR(255) UNIQUE,
  user_password VARCHAR(255),
  avatar VARCHAR(255)
);

CREATE TABLE posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date_creation DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  post_title VARCHAR(255) NOT NULL,
  content VARCHAR(255),
  quote_author VARCHAR(255),
  post_image VARCHAR(255),
  post_video VARCHAR(255) ,
  website_Link VARCHAR(255),
  views_number INT DEFAULT 0,
  author_id INT,
  content_type_id INT,
  hashtag_id INT
);

CREATE TABLE comments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  creation_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  comment_content VARCHAR(255) NOT NULL,
  author_id INT,
  post_id INT
);

CREATE TABLE likes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  liked_post INT
);

CREATE TABLE subscription (
  id INT AUTO_INCREMENT PRIMARY KEY,
  follower INT,
  user_id INT
);

CREATE TABLE messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  message_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  message_content VARCHAR(255),
  sender INT,
  recipient INT
);

CREATE TABLE hashtags (
  id INT AUTO_INCREMENT PRIMARY KEY,
  hashtag VARCHAR(255)
);

CREATE TABLE content_type (
  id INT AUTO_INCREMENT PRIMARY KEY,
  content_title VARCHAR(255) NOT NULL,
  icon_class VARCHAR(255) NOT NULL
);
