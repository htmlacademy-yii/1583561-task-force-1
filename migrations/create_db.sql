DROP TABLE IF EXISTS notifications;
DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS opinions;
DROP TABLE IF EXISTS attached_files;
DROP TABLE IF EXISTS portfolio_photos;
DROP TABLE IF EXISTS user_categories;
DROP TABLE IF EXISTS link_users_categories;
DROP TABLE IF EXISTS proposals;
DROP TABLE IF EXISTS tasks;
DROP TABLE IF EXISTS statuses;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS cities;

-- Категории
-- data/categories.csv
CREATE TABLE categories (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(32) CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (id)
);

-- Города
-- data/cities.csv
CREATE TABLE cities (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(32) CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL, 
  lat DOUBLE NOT NULL,
  lng DOUBLE NOT NULL,
  PRIMARY KEY (id)
);


-- Статусы заказов
CREATE TABLE statuses (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(32) CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (id)
);

-- Пользователи
-- data/users.csv
CREATE TABLE users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  email VARCHAR(32) CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  name VARCHAR(128) CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  password_hash CHAR(32) CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  --
  -- Дополнительные параметры
  --
  -- Город
  city INT UNSIGNED NOT NULL,
  -- День рождения
  birth_date DATE NOT NULL,
  -- Информация о себе
  about TEXT CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  -- Контакты
  phone VARCHAR(16) CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  skype VARCHAR(16) CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  telegram VARCHAR(16) CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  FOREIGN KEY (city) REFERENCES cities(id) ON DELETE CASCADE,
  PRIMARY KEY (id),
  --
  -- Опции
  --
  opt_new_message BOOLEAN NOT NULL DEFAULT(false),
  opt_task_status BOOLEAN NOT NULL DEFAULT(false),
  opt_new_opinion BOOLEAN NOT NULL DEFAULT(false),
  opt_hide_contacts BOOLEAN NOT NULL DEFAULT(false),
  opt_hide_profile BOOLEAN NOT NULL DEFAULT(false)
);

-- Заказы
-- data/tasks.csv
CREATE TABLE tasks (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  created_at DATETIME NOT NULL,
  category_id INT UNSIGNED NOT NULL,
  description TEXT CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  expire DATETIME NOT NULL,
  name TEXT CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  adress TEXT CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  budget INT UNSIGNED NOT NULL,
  lat DOUBLE NOT NULL,
  lng DOUBLE NOT NULL,
  status_id INT UNSIGNED NOT NULL,
  client_id INT UNSIGNED NOT NULL,
  contractor_id INT UNSIGNED NULL,
  FOREIGN KEY (contractor_id) REFERENCES users (id) ON DELETE CASCADE,
  FOREIGN KEY (client_id) REFERENCES users (id) ON DELETE CASCADE,
  FOREIGN KEY (status_id) REFERENCES statuses(id) ON DELETE CASCADE,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
  PRIMARY KEY (id)
);

CREATE TABLE user_categories (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(64) CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL, 
  PRIMARY KEY (id)
);

-- Связь пользователи-категории
CREATE TABLE link_users_categories (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id INT UNSIGNED NOT NULL,
  category_id INT UNSIGNED NOT NULL,
  enabled BOOLEAN NOT NULL DEFAULT(false),
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE,
  PRIMARY KEY (id)  
);

-- Фото работ
CREATE TABLE portfolio_photos (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  created_at DATETIME NOT NULL,
  user_id INT UNSIGNED NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  PRIMARY KEY (id)
);

-- Файлы к заданию
CREATE TABLE attached_files (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(256) CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  created_at DATETIME NOT NULL,
  task_id INT UNSIGNED NOT NULL,
  FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE,
  PRIMARY KEY (id)  
);

-- Отклики
CREATE TABLE proposals (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  contractor_id INT UNSIGNED NOT NULL,
  proposal TEXT CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  added DATETIME NOT NULL,
  price INT UNSIGNED NULL,
  task_id INT UNSIGNED NOT NULL,
  FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE,
  FOREIGN KEY (contractor_id) REFERENCES users(id) ON DELETE CASCADE,
  PRIMARY KEY (id)  
);


-- Отзывы
-- data/opinions.csv
CREATE TABLE opinions (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  created_at DATETIME NOT NULL,
  rate SMALLINT UNSIGNED NOT NULL,
  description TEXT CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  task_id INT UNSIGNED NOT NULL,
  FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE,
  PRIMARY KEY (id)
);

-- Чат
CREATE TABLE messages (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  message TEXT CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  user_from_id INT UNSIGNED NOT NULL,
  from_client BOOLEAN NOT NULL DEFAULT(false),
  task_id INT UNSIGNED NOT NULL,
  created_at DATETIME NOT NULL,
  FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE,
  FOREIGN KEY (user_from_id) REFERENCES users(id) ON DELETE CASCADE,
  PRIMARY KEY (id)
);

-- Уведомления
CREATE TABLE notifications (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  type INT UNSIGNED NOT NULL,
  task_id INT UNSIGNED NOT NULL,
  created_at DATETIME NOT NULL,
  FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE CASCADE,
  PRIMARY KEY (id)
);