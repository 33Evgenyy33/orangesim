CREATE TABLE `wp_revslider_sliders` (  `id` int(9) NOT NULL AUTO_INCREMENT,  `title` tinytext NOT NULL,  `alias` tinytext,  `params` longtext NOT NULL,  `settings` text,  `type` varchar(191) NOT NULL DEFAULT '',  UNIQUE KEY `id` (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40000 ALTER TABLE `wp_revslider_sliders` DISABLE KEYS */;
SET FOREIGN_KEY_CHECKS = 0;
SET UNIQUE_CHECKS = 0;
/*!40000 ALTER TABLE `wp_revslider_sliders` ENABLE KEYS */;
SET FOREIGN_KEY_CHECKS = 1;
SET UNIQUE_CHECKS = 1;
