-- Active: 1664892261064@@127.0.0.1@3306@food_delivery_service
SHOW DATABASES;

CREATE DATABASE IF NOT EXISTS food_delivery_service;

USE food_delivery_service;

CREATE TABLE partners (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(150) NOT NULL,
    description text,
    address VARCHAR(255) NOT NULL
);

CREATE TABLE positions (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description text,
    price INT UNSIGNED NOT NULL DEFAULT(0),
    photo_url VARCHAR(255),
    partner_id INT UNSIGNED,
    FOREIGN KEY (partner_id) REFERENCES partners(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE clients (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    phone CHAR(12),
    fullname VARCHAR(255)
);

CREATE TABLE orders (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    created_at datetime NOT NULL,
    address VARCHAR(255) NOT NULL,
    latitude FLOAT NOT NULL,
    longitude FLOAT NOT NULL,
    STATUS enum(
        'новый',
        'принят рестораном',
        'доставляется',
        'завершен'
    ) NOT NULL,
    client_id INT UNSIGNED NOT NULL,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE orders_positions (
    order_id INT UNSIGNED NOT NULL,
    position_id INT UNSIGNED NOT NULL,
    PRIMARY KEY(order_id, position_id),
    FOREIGN KEY (order_id) REFERENCES orders(id) ON
    UPDATE CASCADE ON
    DELETE CASCADE,
    FOREIGN KEY (position_id) REFERENCES positions(id) ON UPDATE CASCADE ON DELETE RESTRICT
);

-- Задание 1
-- Заполните базу тестовыми данными, не менее 3 партнеров, не менее 3 позиций для заказа у каждого из партнеров. 3 Пользователя, у каждого из которых от 1 до 5 заказов, в каждом из заказов от 1 до 3 позиций блюд
INSERT INTO
    partners (title, description, address)
VALUES
    (
        'Degirmen',
        'кафе быстрого питания',
        '​улица Байтурсынова, 63'
    ),
    (
        'McDonald`s',
        'сеть ресторанов быстрого обслуживания',
        'улица Шевченко, 85а'
    ),
    (
        'Chaihana NAVAT',
        'ресторан',
        'проспект Абылай хана, 58а'
    );

SELECT
    *
FROM
    partners;

INSERT INTO
    positions(title, description, price, photo_url, partner_id)
VALUES
    (
        'чечевичный суп',
        'вкусный',
        850,
        'url_degirmen_1',
        1
    ),
    ('борщ', 'наваристый', 800, 'url_degirmen_2', 1),
    (
        'донер куринный',
        'отменный',
        1100,
        'url_degirmen_3',
        1
    ),
    (
        'гамбургер',
        'Рубленый бифштекс из натуральной цельной говядины на карамелизованной булочке, заправленной горчицей, кетчупом, луком и кусочком маринованного огурчика.',
        600,
        'url_mac_1',
        2
    ),
    (
        'Биг Тейсти ролл',
        'Два сочных бифштекса из натуральной говядины, обернутые в пшеничную лепёшку с добавлением специального соуса «Биг Тейсти», свежих овощей и сыра.',
        1500,
        'url_mac_2',
        2
    ),
    (
        'Соус 1000 островов',
        'он везде',
        150,
        'url_mac_3',
        2
    ),
    (
        'беш-бармак с кониной',
        'Домашнее тесто с мясом конины и наваристым бульоном из мяса ягненка',
        2650,
        'url_navat_1',
        3
    ),
    (
        'гуйру-ганфан',
        'Яркий подлив из мяса ягненка и свежих овощей с отварным рисом',
        1650,
        'url_navat_2',
        3
    ),
    (
        'фрикассе',
        'Нежная курочка с грибами под сливочным соусом',
        1860,
        'url_navat_3',
        3
    );

SELECT
    *
FROM
    positions;

INSERT INTO
    clients (phone, fullname)
VALUES
    ('+77051850128', 'Sevryugin Ilya'),
    ('+77771234567', 'Mister X'),
    ('+77017777777', 'Some other person');

SELECT
    *
FROM
    clients;

INSERT INTO
    orders (
        created_at,
        address,
        latitude,
        longitude,
        STATUS,
        client_id
    )
VALUES
    (
        '2022-10-06 18:45:00',
        'улица Манаса, 60',
        '43.227715',
        '76.910311',
        'новый',
        1
    ),
    (
        '2022-10-06 16:45:05',
        'улица Розыбакиева, 206',
        '43.225295',
        '76.891711',
        'доставляется',
        2
    ),
    (
        '2022-10-06 17:35:00',
        'улица Розыбакиева, 206',
        '43.225295',
        '76.891711',
        'принят рестораном',
        2
    ),
    (
        '2022-10-01 17:00:30',
        'Медео',
        '43.157986',
        '77.059604',
        'завершен',
        3
    ),
    (
        '2022-10-02 17:00:30',
        'Медео',
        '43.157986',
        '77.059604',
        'завершен',
        3
    ),
    (
        '2022-10-06 17:00:30',
        'Медео',
        '43.157986',
        '77.059604',
        'доставляется',
        3
    );

SELECT
    *
FROM
    orders;

INSERT INTO
    orders_positions (order_id, position_id)
VALUES
    (1, 4),
    (1, 5),
    (1, 6),
    (2, 1),
    (3, 2),
    (4, 7),
    (4, 8),
    (5, 1),
    (5, 3),
    (6, 7);

SELECT
    *
FROM
    orders_positions
ORDER BY
    `order_id` ASC;

-- Задание 2
-- Напишите запрос, который будет выводить номера заказов (их ИД), номер телефонов клиентов, название партнера
SELECT
    DISTINCT orders.id AS "Order num",
    clients.phone AS "Client's phone",
    partners.title AS "Partner's name"
FROM
    orders
    JOIN clients ON orders.client_id = clients.id
    JOIN orders_positions ON orders.id = orders_positions.order_id
    JOIN positions ON orders_positions.position_id = positions.id
    JOIN partners ON positions.partner_id = partners.id;

-- Задание 3
-- Добавьте еще одного партнера и минимум 1 позицию для него. Но не создавайте заказы. Сделайте запрос, который выведет таких партнеров, у которых еще не было ни одного заказа
INSERT INTO
    partners(title, description, address)
VALUES
    (
        'KFC',
        'предприятие быстрого обслуживания',
        '​улица Жансугурова, 273/2'
    );

INSERT INTO
    positions(title, description, price, photo_url, partner_id)
VALUES
    (
        'ФРЕНДС БОКС "АССОРТИ"',
        '5 ножек, 10 острых крыльев, 5 стрипсов, байтс 270 гр, баскет фри',
        8250,
        'url_kfc_1',
        4
    );

SELECT
    title AS "No orders from"
FROM
    partners
WHERE
    id NOT IN (
        SELECT
            DISTINCT partner_id
        FROM
            orders_positions
            JOIN positions ON positions.id = orders_positions.position_id
    );

-- Задание 4
-- Напишите запрос, который по ID пользователя и ID заказа выведет названия всех позиций из этого заказа.
SET
    @order = 1,
    @client = 1;

SELECT
    positions.id AS "Ordered positions",
    positions.title AS "Title"
FROM
    orders
    JOIN clients ON orders.client_id = clients.id
    JOIN orders_positions ON orders.id = orders_positions.order_id
    JOIN positions ON orders_positions.position_id = positions.id
WHERE
    orders.id = @order
    AND clients.id = @client;