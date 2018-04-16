CREATE TABLE `wp_rg_form` (  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,  `title` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,  `date_created` datetime NOT NULL,  `is_active` tinyint(1) NOT NULL DEFAULT '1',  `is_trash` tinyint(1) NOT NULL DEFAULT '0',  PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40000 ALTER TABLE `wp_rg_form` DISABLE KEYS */;
SET FOREIGN_KEY_CHECKS = 0;
SET UNIQUE_CHECKS = 0;
INSERT INTO `wp_rg_form` VALUES('1', 'Получить бесплатную консультацию по выбору тарифа', '2018-03-22 11:16:14', '1', '0');
INSERT INTO `wp_rg_form` VALUES('2', 'Обратная связь', '2018-03-23 11:20:00', '1', '0');
/*!40000 ALTER TABLE `wp_rg_form` ENABLE KEYS */;
SET FOREIGN_KEY_CHECKS = 1;
SET UNIQUE_CHECKS = 1;
