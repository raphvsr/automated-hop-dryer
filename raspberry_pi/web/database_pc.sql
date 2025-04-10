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
  `drying_campaigns` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `variety_id` INT NOT NULL,
    `start_time` DATETIME NOT NULL,
    `end_time` DATETIME NULL
  );

CREATE TABLE
  `drying_stages` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `campaign_id` INT NOT NULL,
    `stage_number` INT NOT NULL,
    `stage_name` VARCHAR(50) NOT NULL
  );

CREATE TABLE
  `alerts` (
    `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `campaign_id` INT NOT NULL,
    `alert_time` DATETIME NOT NULL,
    `alert_type` ENUM ('') NOT NULL,
    `message` TEXT NOT NULL
  );

CREATE TABLE
  `action_history` (
    `action_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `campaign_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `action_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
    `action_type` VARCHAR(255) NOT NULL,
    `action_detail` TEXT NOT NULL
  );

CREATE TABLE
  `burner_status` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `campaign_id` INT NOT NULL,
    `status` ENUM ('on', 'off') NOT NULL,
    `changed_at` TIMESTAMP NOT NULL
  );

ALTER TABLE `drying_stages` ADD CONSTRAINT `drying_stages_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `drying_campaigns` (`id`);

ALTER TABLE `alerts` ADD CONSTRAINT `alerts_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `drying_campaigns` (`id`);

ALTER TABLE `burner_status` ADD CONSTRAINT `burner_status_campaigns_id_foreign` FOREIGN KEY (`campaigns_id`) REFERENCES `drying_campaigns` (`id`);

ALTER TABLE `action_history` ADD CONSTRAINT `action_history_campaign_id_foreign` FOREIGN KEY (`campaign_id`) REFERENCES `drying_campaigns` (`id`);

ALTER TABLE `action_history` ADD CONSTRAINT `action_history_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
