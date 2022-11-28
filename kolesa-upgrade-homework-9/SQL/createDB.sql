-- Active: 1664892261064@@127.0.0.1@3306@adverts

SHOW DATABASES;

CREATE DATABASE IF NOT EXISTS adverts;

USE adverts;

DROP TABLE IF EXISTS adverts;

CREATE TABLE
    adverts (
        `id` INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
        `title` VARCHAR(255) NOT NULL,
        `description` VARCHAR(255) NOT NULL,
        `price` INT DEFAULT(0)
    );

INSERT INTO
    adverts (
        `id`,
        `title`,
        `description`,
        `price`
    )
VALUES (
        1,
        'Смартфон Apple iPhone 7 32GB Gold',
        'Операционная система: iOS 10 / Количество SIM-карт: 1 / Диагональ дисплея: 4,7″ - 11,9 см / Объем встроенной памяти: 32 GB / Основная камера: 12 Mpx / Фронтальная камера: 7 Mpx / NFC: Да',
        149990
    ), (
        2,
        'Чайник ARG W-K18465G',
        'Мощность: 2200 Вт / Объем: 1,8 л. / Тип нагревательного элемента: Скрытый / Управление со смартфона: Нет / Страна производителя: Китай / Защита от включения без воды: Да / Отключение при снятии с подставки: Да / Подсветка: Да /',
        12990
    ), (
        3,
        'Адаптер Apple 20W USB-C (MHJE3ZM)',
        'Тип: Сетевое ЗУ / Быстрая зарядка: Да / Количество заряжаемых устройств: 1 / Страна производителя: Китай / Максимальное входное напряжение: 220 В / Особенности: Этот адаптер совместим с любыми устройствами Apple',
        14990
    );

SELECT * FROM adverts;

