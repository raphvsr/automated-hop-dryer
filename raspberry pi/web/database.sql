CREATE TABLE
  `users` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `role` BOOLEAN NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP()
  );

ALTER TABLE `users` ADD UNIQUE `users_username_unique` (`username`);

CREATE TABLE
  `hop_varieties` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `max_temperature` DECIMAL(5, 2) NOT NULL,
    `min_temperature` DECIMAL(5, 2) NOT NULL,
    `duree_de_sechage` VARCHAR(20) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP()
  );

ALTER TABLE `hop_varieties` ADD UNIQUE `hop_varieties_name_unique` (`name`);

CREATE TABLE
  `system_config` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `key_name` VARCHAR(100) NOT NULL,
    `value` TEXT NOT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP()
  );

ALTER TABLE `system_config` ADD UNIQUE `system_config_key_name_unique` (`key_name`);

INSERT INTO
  USERS (username, password_hash, role, created_at)
VALUES
  (
    'admin',
    '$2y$10$fxW/BV8ZwU/qvF4/.Thv7uIkdOuNSlLQYBGe4CsRrzihtIcRuPa4C',
    1,
    '2025-03-12 19:36:09'
  );
