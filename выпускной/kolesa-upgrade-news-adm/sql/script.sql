-- Active: 1664892261064@@127.0.0.1@3306@news_bot
CREATE DATABASE IF NOT EXISTS news;

USE news;

CREATE TABLE IF NOT EXISTS
    messages (
                 id int UNSIGNED not null primary key auto_increment,
                 title varchar(255) NOT NULL,
                 body text,
                 created_at DATETIME NOT NULL DEFAULT NOW()
);