CREATE DATABASE IF NOT EXISTS `linkshortner` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `linkshortner`;

CREATE TABLE IF NOT EXISTS `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `email` VARCHAR(191) NOT NULL,
    `password_hash` VARCHAR(255) NOT NULL,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uniq_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `urls` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NULL,
    `original_url` TEXT NOT NULL,
    `original_url_hash` CHAR(64) NOT NULL,
    `alias` VARCHAR(64) NOT NULL,
    `redirect_type` SMALLINT UNSIGNED NOT NULL DEFAULT 302,
    `expires_at` DATETIME NULL,
    `created_ip` VARCHAR(45) NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `total_clicks` INT UNSIGNED NOT NULL DEFAULT 0,
    `unique_clicks` INT UNSIGNED NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uniq_urls_alias` (`alias`),
    KEY `idx_urls_user_id` (`user_id`),
    KEY `idx_urls_original_hash` (`original_url_hash`),
    KEY `idx_urls_expires_at` (`expires_at`),
    KEY `idx_urls_created_at` (`created_at`),
    CONSTRAINT `fk_urls_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `remember_tokens` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `selector` CHAR(24) NOT NULL,
    `token_hash` CHAR(64) NOT NULL,
    `expires_at` DATETIME NOT NULL,
    `user_agent` VARCHAR(255) NOT NULL,
    `created_ip` VARCHAR(45) NOT NULL,
    `created_at` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uniq_remember_selector` (`selector`),
    KEY `idx_remember_user_id` (`user_id`),
    KEY `idx_remember_expires_at` (`expires_at`),
    CONSTRAINT `fk_remember_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `devices` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `device_name` VARCHAR(60) NOT NULL,
    `created_at` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uniq_devices_name` (`device_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `browsers` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `browser_name` VARCHAR(80) NOT NULL,
    `created_at` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uniq_browsers_name` (`browser_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `countries` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `country_name` VARCHAR(120) NOT NULL,
    `created_at` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uniq_countries_name` (`country_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `referrers` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `referrer_label` VARCHAR(191) NOT NULL,
    `referrer_domain` VARCHAR(191) NOT NULL,
    `created_at` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uniq_referrer_label_domain` (`referrer_label`, `referrer_domain`),
    KEY `idx_referrer_domain` (`referrer_domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `clicks` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `url_id` BIGINT UNSIGNED NOT NULL,
    `tracking_key` CHAR(64) NOT NULL,
    `visitor_id` CHAR(40) NOT NULL,
    `visitor_hash` CHAR(64) NOT NULL,
    `fingerprint_hash` CHAR(64) NULL,
    `ip_address` VARCHAR(45) NOT NULL,
    `country_id` INT UNSIGNED NULL,
    `city` VARCHAR(120) NOT NULL DEFAULT 'Unknown',
    `device_id` INT UNSIGNED NULL,
    `browser_id` INT UNSIGNED NULL,
    `referrer_id` INT UNSIGNED NULL,
    `operating_system` VARCHAR(80) NOT NULL DEFAULT 'Unknown',
    `user_agent` VARCHAR(255) NOT NULL,
    `screen_resolution` VARCHAR(20) NOT NULL DEFAULT '',
    `language` VARCHAR(20) NOT NULL DEFAULT '',
    `timezone` VARCHAR(64) NOT NULL DEFAULT '',
    `is_unique` TINYINT(1) NOT NULL DEFAULT 0,
    `is_bot` TINYINT(1) NOT NULL DEFAULT 0,
    `utm_source` VARCHAR(191) NOT NULL DEFAULT '',
    `utm_medium` VARCHAR(191) NOT NULL DEFAULT '',
    `utm_campaign` VARCHAR(191) NOT NULL DEFAULT '',
    `utm_term` VARCHAR(191) NOT NULL DEFAULT '',
    `utm_content` VARCHAR(191) NOT NULL DEFAULT '',
    `clicked_at` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uniq_click_tracking_key` (`tracking_key`),
    KEY `idx_clicks_url_time` (`url_id`, `clicked_at`),
    KEY `idx_clicks_url_visitor` (`url_id`, `visitor_hash`),
    KEY `idx_clicks_url_country` (`url_id`, `country_id`, `clicked_at`),
    KEY `idx_clicks_url_device` (`url_id`, `device_id`, `clicked_at`),
    KEY `idx_clicks_url_browser` (`url_id`, `browser_id`, `clicked_at`),
    KEY `idx_clicks_url_referrer` (`url_id`, `referrer_id`, `clicked_at`),
    KEY `idx_clicks_bot_time` (`is_bot`, `clicked_at`),
    CONSTRAINT `fk_clicks_url` FOREIGN KEY (`url_id`) REFERENCES `urls` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_clicks_country` FOREIGN KEY (`country_id`) REFERENCES `countries` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_clicks_device` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_clicks_browser` FOREIGN KEY (`browser_id`) REFERENCES `browsers` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_clicks_referrer` FOREIGN KEY (`referrer_id`) REFERENCES `referrers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



INSERT IGNORE INTO `devices` (`id`, `device_name`, `created_at`) VALUES
    (1, 'Desktop', NOW()),
    (2, 'Mobile', NOW()),
    (3, 'Tablet', NOW());

INSERT IGNORE INTO `browsers` (`id`, `browser_name`, `created_at`) VALUES
    (1, 'Chrome', NOW()),
    (2, 'Firefox', NOW()),
    (3, 'Safari', NOW()),
    (4, 'Edge', NOW()),
    (5, 'Unknown', NOW());

INSERT IGNORE INTO `countries` (`id`, `country_name`, `created_at`) VALUES
    (1, 'Unknown', NOW());

INSERT IGNORE INTO `referrers` (`id`, `referrer_label`, `referrer_domain`, `created_at`) VALUES
    (1, 'Direct / Unknown', 'direct', NOW());
