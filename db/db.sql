CREATE TABLE `persons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state` varchar(32) COLLATE utf8mb4_swedish_ci NOT NULL DEFAULT 'ready',
  `updated` datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `external_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_swedish_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_swedish_ci DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;