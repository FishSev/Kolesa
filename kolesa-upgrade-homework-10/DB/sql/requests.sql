sqlite3 upgrade.db 

-- создаём таблицу пользователей
CREATE TABLE
    users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name varchar(255),
        telegram_id INT,
        first_name varchar(255),
        last_name varchar(255),
        chat_id INT
    );

-- создаём таблицу задач
CREATE TABLE
    tasks (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title varchar(255),
        description varchar(255),
        end_date date,
        user_id INTEGER NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE ON UPDATE CASCADE
    );

.quit 