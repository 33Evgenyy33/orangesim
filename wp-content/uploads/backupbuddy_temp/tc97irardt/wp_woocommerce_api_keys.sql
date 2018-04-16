CREATE TABLE `wp_woocommerce_api_keys` (  `key_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,  `user_id` bigint(20) unsigned NOT NULL,  `description` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,  `permissions` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,  `consumer_key` char(64) COLLATE utf8mb4_unicode_ci NOT NULL,  `consumer_secret` char(43) COLLATE utf8mb4_unicode_ci NOT NULL,  `nonces` longtext COLLATE utf8mb4_unicode_ci,  `truncated_key` char(7) COLLATE utf8mb4_unicode_ci NOT NULL,  `last_access` datetime DEFAULT NULL,  PRIMARY KEY (`key_id`),  KEY `consumer_key` (`consumer_key`),  KEY `consumer_secret` (`consumer_secret`)) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40000 ALTER TABLE `wp_woocommerce_api_keys` DISABLE KEYS */;
SET FOREIGN_KEY_CHECKS = 0;
SET UNIQUE_CHECKS = 0;
INSERT INTO `wp_woocommerce_api_keys` VALUES('1', '1', 'seller', 'read_write', 'af2609adaa71f85b16b1afba0138be2079169c086096d7dd4e73417f72248074', 'cs_bb26e3502968eff1e1f438c7c2f15308206ca8fc', NULL, 'a40042f', '2018-04-13 16:28:36');
INSERT INTO `wp_woocommerce_api_keys` VALUES('2', '1', 'test', 'read_write', '73d2b5397f124364378de5e070494f18e50afbe7b1f5ad572b853091d65c7082', 'cs_f782fa20ebc745a38a581833ad98c7d45342939d', NULL, 'ef17083', '2018-03-28 13:21:47');
/*!40000 ALTER TABLE `wp_woocommerce_api_keys` ENABLE KEYS */;
SET FOREIGN_KEY_CHECKS = 1;
SET UNIQUE_CHECKS = 1;
