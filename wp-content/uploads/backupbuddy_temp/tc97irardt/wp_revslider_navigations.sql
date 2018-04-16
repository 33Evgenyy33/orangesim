CREATE TABLE `wp_revslider_navigations` (  `id` int(9) NOT NULL AUTO_INCREMENT,  `name` varchar(191) NOT NULL,  `handle` varchar(191) NOT NULL,  `css` longtext NOT NULL,  `markup` longtext NOT NULL,  `settings` longtext,  UNIQUE KEY `id` (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40000 ALTER TABLE `wp_revslider_navigations` DISABLE KEYS */;
SET FOREIGN_KEY_CHECKS = 0;
SET UNIQUE_CHECKS = 0;
/*!40000 ALTER TABLE `wp_revslider_navigations` ENABLE KEYS */;
SET FOREIGN_KEY_CHECKS = 1;
SET UNIQUE_CHECKS = 1;
