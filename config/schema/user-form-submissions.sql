CREATE TABLE `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `appwrite_user_id` VARCHAR(64) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `display_name` VARCHAR(255) NOT NULL,
    `email_verified` TINYINT(1) NOT NULL DEFAULT 0,
    `last_login_at` DATETIME NULL,
    `created` DATETIME NOT NULL,
    `modified` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_appwrite_user_id_unique` (`appwrite_user_id`),
    UNIQUE KEY `users_email_unique` (`email`),
    KEY `users_last_login_at_idx` (`last_login_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `forms` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `slug` VARCHAR(191) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    `form_class` VARCHAR(255) NOT NULL,
    `schema_path` VARCHAR(255) NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created` DATETIME NOT NULL,
    `modified` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `forms_slug_unique` (`slug`),
    UNIQUE KEY `forms_form_class_unique` (`form_class`),
    KEY `forms_is_active_idx` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `submissions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `form_id` BIGINT UNSIGNED NOT NULL,
    `payload` JSON NOT NULL,
    `ip_address` VARCHAR(45) NULL,
    `user_agent` TEXT NULL,
    `referrer_url` TEXT NULL,
    `accept_language` VARCHAR(255) NULL,
    `source_url` TEXT NULL,
    `reviewed` TINYINT(1) NOT NULL DEFAULT 0,
    `review_notes` TEXT NULL,
    `created` DATETIME NOT NULL,
    `modified` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    KEY `submissions_reviewed_idx` (`reviewed`),
    KEY `submissions_form_created_idx` (`form_id`, `created`),
    CONSTRAINT `submissions_form_id_fk`
        FOREIGN KEY (`form_id`) REFERENCES `forms` (`id`)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
