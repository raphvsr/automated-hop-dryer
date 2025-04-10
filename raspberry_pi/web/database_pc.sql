CREATE TABLE `users`(
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `role` BOOLEAN NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP());
ALTER TABLE
    `users` ADD UNIQUE `users_username_unique`(`username`);
CREATE TABLE `varieties`(
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL
);
CREATE TABLE `drying_campaigns`(
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `variety_id` INT NOT NULL,
    `start_time` DATETIME NOT NULL,
    `end_time` DATETIME NULL DEFAULT 'DEFAULT NULL'
);
CREATE TABLE `drying_etages`(
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `campaign_id` INT NOT NULL,
    `etage_number` INT NOT NULL,
    `start_time` DATETIME NOT NULL,
    `end_time` DATETIME NULL DEFAULT 'DEFAULT NULL'
);
CREATE TABLE `burner_status`(
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `campaign_id` INT NOT NULL,
    `etage_number` INT NOT NULL,
    `status` ENUM('') NOT NULL,
    `changed_at` TIMESTAMP NOT NULL
);
CREATE TABLE `alerts`(
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `campaign_id` INT NOT NULL,
    `etage_number` INT NOT NULL,
    `alert_time` DATETIME NOT NULL,
    `alert_type` VARCHAR(100) NOT NULL,
    `message` TEXT NOT NULL
);
CREATE TABLE `action_history`(
    `action_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `campaign_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `action_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(), `action_type` VARCHAR(255) NOT NULL, `action_detail` TEXT NOT NULL);
ALTER TABLE
    `alerts` ADD CONSTRAINT `alerts_campaign_id_foreign` FOREIGN KEY(`campaign_id`) REFERENCES `drying_campaigns`(`id`);
ALTER TABLE
    `burner_status` ADD CONSTRAINT `burner_status_campaign_id_foreign` FOREIGN KEY(`campaign_id`) REFERENCES `drying_campaigns`(`id`);
ALTER TABLE
    `drying_etages` ADD CONSTRAINT `drying_etages_campaign_id_foreign` FOREIGN KEY(`campaign_id`) REFERENCES `drying_campaigns`(`id`);
ALTER TABLE
    `drying_campaigns` ADD CONSTRAINT `drying_campaigns_variety_id_foreign` FOREIGN KEY(`variety_id`) REFERENCES `varieties`(`id`);
ALTER TABLE
    `action_history` ADD CONSTRAINT `action_history_campaign_id_foreign` FOREIGN KEY(`campaign_id`) REFERENCES `drying_campaigns`(`id`);
ALTER TABLE
    `action_history` ADD CONSTRAINT `action_history_user_id_foreign` FOREIGN KEY(`user_id`) REFERENCES `users`(`id`);
