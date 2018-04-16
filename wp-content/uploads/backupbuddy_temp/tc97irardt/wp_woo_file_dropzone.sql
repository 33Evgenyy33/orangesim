CREATE TABLE `wp_woo_file_dropzone` (  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,  `extra_fields` longtext,  `file_urls` longtext,  `product_id` int(11) unsigned DEFAULT NULL,  `order_id` int(11) unsigned DEFAULT NULL,  `order_status` varchar(50) DEFAULT NULL,  `session_id` varchar(300) DEFAULT NULL,  `session_expiry_time` varchar(300) DEFAULT NULL,  `variation_id` int(11) DEFAULT NULL,  PRIMARY KEY (`id`),  UNIQUE KEY `id_UNIQUE` (`id`)) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40000 ALTER TABLE `wp_woo_file_dropzone` DISABLE KEYS */;
SET FOREIGN_KEY_CHECKS = 0;
SET UNIQUE_CHECKS = 0;
/*!40000 ALTER TABLE `wp_woo_file_dropzone` ENABLE KEYS */;
SET FOREIGN_KEY_CHECKS = 1;
SET UNIQUE_CHECKS = 1;
