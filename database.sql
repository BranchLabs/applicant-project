DROP TABLE IF EXISTS `contacts`;
CREATE TABLE `contacts` (
    `id` VARCHAR(36) NOT NULL PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `surname` VARCHAR(255) DEFAULT NULL NULL,
    `email` VARCHAR(255) NOT NULL,
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME DEFAULT NULL NULL,
    UNIQUE KEY unique_email (email)
);
