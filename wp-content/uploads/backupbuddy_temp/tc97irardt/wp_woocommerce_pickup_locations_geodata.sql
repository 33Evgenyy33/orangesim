CREATE TABLE `wp_woocommerce_pickup_locations_geodata` (  `post_id` bigint(20) unsigned NOT NULL DEFAULT '0',  `lat` decimal(11,6) NOT NULL DEFAULT '0.000000',  `lon` decimal(11,6) NOT NULL DEFAULT '0.000000',  `title` text COLLATE utf8mb4_unicode_ci NOT NULL,  `state` varchar(180) COLLATE utf8mb4_unicode_ci DEFAULT NULL,  `country` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',  `postcode` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',  `city` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',  `address_1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',  `address_2` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',  `last_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',  PRIMARY KEY (`post_id`),  UNIQUE KEY `post_id` (`post_id`),  KEY `coordinates` (`lat`,`lon`),  KEY `country_state` (`country`,`state`)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40000 ALTER TABLE `wp_woocommerce_pickup_locations_geodata` DISABLE KEYS */;
SET FOREIGN_KEY_CHECKS = 0;
SET UNIQUE_CHECKS = 0;
INSERT INTO `wp_woocommerce_pickup_locations_geodata` VALUES('1239', '0.000000', '0.000000', 'Vacanta Travel', 'г Москва', 'RU', '', 'м. Смоленская, ул. Арбат, д.54/2, стр.1, оф. 460', 'Москва', '', '2018-02-26 08:32:22');
INSERT INTO `wp_woocommerce_pickup_locations_geodata` VALUES('1240', '0.000000', '0.000000', 'Фан-Тур', 'г Москва', 'RU', '', 'Свердлова, 2', 'Новокузнецк', '', '2018-02-26 08:32:18');
/*!40000 ALTER TABLE `wp_woocommerce_pickup_locations_geodata` ENABLE KEYS */;
SET FOREIGN_KEY_CHECKS = 1;
SET UNIQUE_CHECKS = 1;
