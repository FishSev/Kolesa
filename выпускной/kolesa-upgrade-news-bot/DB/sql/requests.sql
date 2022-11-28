-- Active: 1664892261064@@127.0.0.1@3306@adverts

SHOW DATABASES;

-- DROP DATABASE news_bot;

CREATE DATABASE IF NOT EXISTS news_bot;

USE news_bot;

SHOW TABLES;

CREATE TABLE IF NOT EXISTS
    users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name varchar(255),
        telegram_id INT,
        first_name varchar(255),
        last_name varchar(255),
        chat_id INT
    );

SELECT * FROM users;

-- DELETE FROM users WHERE id = 1;