CREATE TABLE `wp_revslider_layer_animations` (  `id` int(9) NOT NULL AUTO_INCREMENT,  `handle` text NOT NULL,  `params` text NOT NULL,  `settings` text,  UNIQUE KEY `id` (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40000 ALTER TABLE `wp_revslider_layer_animations` DISABLE KEYS */;
SET FOREIGN_KEY_CHECKS = 0;
SET UNIQUE_CHECKS = 0;
/*!40000 ALTER TABLE `wp_revslider_layer_animations` ENABLE KEYS */;
SET FOREIGN_KEY_CHECKS = 1;
SET UNIQUE_CHECKS = 1;
