-- SQL scaffold for projects table used by main_view/Cliente/projects_view.php
-- Run this in your MySQL client (e.g., phpMyAdmin or mysql CLI) against the myschool_integrator database.

CREATE TABLE IF NOT EXISTS `projects` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `authors` VARCHAR(255) DEFAULT NULL,
  `year` INT DEFAULT NULL,
  `file_path` VARCHAR(512) DEFAULT NULL,
  `status` VARCHAR(50) DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Sample inserts
INSERT INTO `projects` (`title`, `description`, `authors`, `year`, `file_path`, `status`) VALUES
('Proyecto Alpha', 'Descripción del proyecto Alpha', 'Equipo A', 2023, NULL, 'active'),
('Proyecto Beta', 'Descripción del proyecto Beta', 'Equipo B', 2024, NULL, 'archived');
