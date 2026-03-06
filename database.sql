CREATE DATABASE IF NOT EXISTS STUDYORGANIZER;
USE STUDYORGANIZER;

CREATE TABLE `subject` (
    `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL
);

CREATE TABLE `user` (
    `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(255) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `auth_key` VARCHAR(32) NOT NULL,
    `role` VARCHAR(50) DEFAULT 'user'
);

CREATE TABLE `teacher` (
    `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `subject_id` INT(11) NOT NULL,
    `is_active` TINYINT(1) DEFAULT 1,
    CONSTRAINT `fk_teacher_subject` FOREIGN KEY (`subject_id`) REFERENCES `subject`(`id`) ON DELETE RESTRICT
);

CREATE TABLE `homework` (
    `id` INT(11) AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT(11) NOT NULL,
    `subject_id` INT(11) NOT NULL,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT,
    `due_date` DATE NOT NULL,
    `is_finished` TINYINT(1) DEFAULT 0,
    CONSTRAINT `fk_homework_user` FOREIGN KEY (`user_id`) REFERENCES `user`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_homework_subject` FOREIGN KEY (`subject_id`) REFERENCES `subject`(`id`) ON DELETE CASCADE
);

INSERT INTO `subject` (`name`) VALUES
('Mathematik'),
('Deutsch'),
('Englisch'),
('Insy'),
('Physik');

INSERT INTO `user` (`username`, `password_hash`, `auth_key`, `role`) VALUES
('admin_user', '$2y$13$dummyhashedpassword123456789012345678901234', 'randomauthkey1', 'admin'),
('test_student', '$2y$13$dummyhashedpassword123456789012345678901234', 'randomauthkey2', 'user');

INSERT INTO `teacher` (`name`, `subject_id`, `is_active`) VALUES
('Hr. Müller', 1, 1),
('Fr. Schmidt', 2, 1),
('Hr. Schuster', 5, 0);

INSERT INTO `homework` (`user_id`, `subject_id`, `title`, `description`, `due_date`, `is_finished`) VALUES
(2, 1, 'Hausübung 4', 'Beispielbeschreibung', '2026-02-28', 0),
(2, 2, 'Aufsatz', 'Beispielbeschreibung', '2026-02-22', 0),
(2, 3, 'Vokabeltest', 'Beispielbeschreibung', '2026-02-17', 0),
(2, 4, 'SQL Mockup', 'Beispielbeschreibung', '2026-02-24', 0),
(2, 5, 'Protokoll', 'Beispielbeschreibung', '2026-02-01', 1);