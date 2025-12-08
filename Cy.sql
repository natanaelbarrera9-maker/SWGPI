DROP TABLE IF EXISTS `asignaturas`;

CREATE TABLE `asignaturas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `clave` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nombre` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `avances`;

CREATE TABLE `avances` (
  `id` int NOT NULL AUTO_INCREMENT,
  `project_id` int NOT NULL,
  `note` text,
  `milestone_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  CONSTRAINT `avances_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `avances` WRITE;

UNLOCK TABLES;

DROP TABLE IF EXISTS `competencias`;

CREATE TABLE `competencias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `asignatura_id` int NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `asignatura_id` (`asignatura_id`),
  CONSTRAINT `competencias_ibfk_1` FOREIGN KEY (`asignatura_id`) REFERENCES `asignaturas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

DROP TABLE IF EXISTS `entregables`;

CREATE TABLE `entregables` (
  `id` int NOT NULL AUTO_INCREMENT,
  `competencia_id` int NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `fecha_limite` datetime NOT NULL,
  `formatos_aceptados` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `competencia_id` (`competencia_id`),
  CONSTRAINT `entregables_ibfk_1` FOREIGN KEY (`competencia_id`) REFERENCES `competencias` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


DROP TABLE IF EXISTS `entregables_antiguos`;

CREATE TABLE `entregables_antiguos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `project_id` int NOT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  CONSTRAINT `entregables_antiguos_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `entregables_antiguos` WRITE;

UNLOCK TABLES;

DROP TABLE IF EXISTS `entregas_estudiantes`;

CREATE TABLE `entregas_estudiantes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `entregable_id` int NOT NULL,
  `user_id` varchar(20) NOT NULL,
  `project_id` int NOT NULL,
  `nombre_archivo` varchar(255) NOT NULL,
  `ruta_archivo` varchar(255) NOT NULL,
  `fecha_entrega` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `calificacion` decimal(5,2) DEFAULT NULL,
  `comentarios_docente` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `entrega_unica` (`entregable_id`,`user_id`,`project_id`),
  KEY `user_id` (`user_id`),
  KEY `project_id` (`project_id`),
  CONSTRAINT `entregas_estudiantes_ibfk_1` FOREIGN KEY (`entregable_id`) REFERENCES `entregables` (`id`) ON DELETE CASCADE,
  CONSTRAINT `entregas_estudiantes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `entregas_estudiantes_ibfk_3` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



DROP TABLE IF EXISTS `feedback`;

CREATE TABLE `feedback` (
  `id` int NOT NULL AUTO_INCREMENT,
  `project_id` int NOT NULL,
  `author` varchar(150) DEFAULT NULL,
  `comment` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `project_id` (`project_id`),
  CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

LOCK TABLES `feedback` WRITE;

UNLOCK TABLES;

DROP TABLE IF EXISTS `grafos`;

CREATE TABLE `grafos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text,
  `nombre_archivo` varchar(255) NOT NULL,
  `status` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `migrations` WRITE;

UNLOCK TABLES;

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

LOCK TABLES `password_resets` WRITE;

UNLOCK TABLES;

DROP TABLE IF EXISTS `project_asignatura`;

CREATE TABLE `project_asignatura` (
  `id` int NOT NULL AUTO_INCREMENT,
  `project_id` int NOT NULL,
  `asignatura_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `project_asignatura_unique` (`project_id`,`asignatura_id`),
  KEY `project_id` (`project_id`),
  KEY `asignatura_id` (`asignatura_id`),
  CONSTRAINT `fk_project_asignatura_asignatura` FOREIGN KEY (`asignatura_id`) REFERENCES `asignaturas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_project_asignatura_project` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

DROP TABLE IF EXISTS `project_user`;

CREATE TABLE `project_user` (
  `project_id` int NOT NULL,
  `user_id` varchar(10) NOT NULL,
  `rol_asesor` enum('primario','secundario') DEFAULT NULL,
  PRIMARY KEY (`project_id`,`user_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `project_user_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `project_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;



DROP TABLE IF EXISTS `projects`;

CREATE TABLE `projects` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `created_by` varchar(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`),
  CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` varchar(10) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombres` varchar(200) NOT NULL,
  `apa` varchar(100) DEFAULT NULL,
  `ama` varchar(100) DEFAULT NULL,
  `direccion` text,
  `telefonos` varchar(200) DEFAULT NULL,
  `curp` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `perfil_id` tinyint NOT NULL DEFAULT '3',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

