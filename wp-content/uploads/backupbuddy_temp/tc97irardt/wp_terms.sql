CREATE TABLE `wp_terms` (  `term_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',  `slug` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',  `term_group` bigint(10) NOT NULL DEFAULT '0',  PRIMARY KEY (`term_id`),  KEY `slug` (`slug`(191)),  KEY `name` (`name`(191))) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40000 ALTER TABLE `wp_terms` DISABLE KEYS */;
SET FOREIGN_KEY_CHECKS = 0;
SET UNIQUE_CHECKS = 0;
INSERT INTO `wp_terms` VALUES('1', 'Без рубрики', 'bez-rubriki', '0');
INSERT INTO `wp_terms` VALUES('2', 'Coding', 'coding', '0');
INSERT INTO `wp_terms` VALUES('3', 'Copywriting', 'copywriting', '0');
INSERT INTO `wp_terms` VALUES('4', 'Creative', 'creative', '0');
INSERT INTO `wp_terms` VALUES('5', 'Design', 'design', '0');
INSERT INTO `wp_terms` VALUES('6', 'Photography', 'photography', '0');
INSERT INTO `wp_terms` VALUES('7', 'Uncategorized', 'uncategorized', '0');
INSERT INTO `wp_terms` VALUES('8', 'blogging', 'blogging', '0');
INSERT INTO `wp_terms` VALUES('9', 'business', 'business', '0');
INSERT INTO `wp_terms` VALUES('10', 'clients', 'clients', '0');
INSERT INTO `wp_terms` VALUES('11', 'coding', 'coding', '0');
INSERT INTO `wp_terms` VALUES('12', 'copywriting', 'copywriting', '0');
INSERT INTO `wp_terms` VALUES('13', 'corporate', 'corporate', '0');
INSERT INTO `wp_terms` VALUES('14', 'design', 'design', '0');
INSERT INTO `wp_terms` VALUES('15', 'gallery', 'gallery', '0');
INSERT INTO `wp_terms` VALUES('16', 'image', 'image', '0');
INSERT INTO `wp_terms` VALUES('17', 'post', 'post', '0');
INSERT INTO `wp_terms` VALUES('18', 'SEO', 'seo', '0');
INSERT INTO `wp_terms` VALUES('19', 'SMM', 'smm', '0');
INSERT INTO `wp_terms` VALUES('20', 'social', 'social', '0');
INSERT INTO `wp_terms` VALUES('21', 'text', 'text', '0');
INSERT INTO `wp_terms` VALUES('22', 'video', 'video', '0');
INSERT INTO `wp_terms` VALUES('23', 'wordpress', 'wordpress', '0');
INSERT INTO `wp_terms` VALUES('24', 'Header Menu', 'header-menu', '0');
INSERT INTO `wp_terms` VALUES('25', 'simple', 'simple', '0');
INSERT INTO `wp_terms` VALUES('26', 'grouped', 'grouped', '0');
INSERT INTO `wp_terms` VALUES('28', 'variable', 'variable', '0');
INSERT INTO `wp_terms` VALUES('29', 'variable', 'variable-2', '0');
INSERT INTO `wp_terms` VALUES('30', 'external', 'external', '0');
INSERT INTO `wp_terms` VALUES('31', 'exclude-from-search', 'exclude-from-search', '0');
INSERT INTO `wp_terms` VALUES('33', 'exclude-from-catalog', 'exclude-from-catalog', '0');
INSERT INTO `wp_terms` VALUES('34', 'featured', 'featured', '0');
INSERT INTO `wp_terms` VALUES('36', 'outofstock', 'outofstock', '0');
INSERT INTO `wp_terms` VALUES('37', 'rated-1', 'rated-1', '0');
INSERT INTO `wp_terms` VALUES('39', 'rated-2', 'rated-2', '0');
INSERT INTO `wp_terms` VALUES('41', 'rated-3', 'rated-3', '0');
INSERT INTO `wp_terms` VALUES('42', 'rated-4', 'rated-4', '0');
INSERT INTO `wp_terms` VALUES('44', 'rated-5', 'rated-5', '0');
INSERT INTO `wp_terms` VALUES('45', 'rated-5', 'rated-5-2', '0');
INSERT INTO `wp_terms` VALUES('46', 'Uncategorized', 'uncategorized', '0');
INSERT INTO `wp_terms` VALUES('47', 'mobile header', 'mobile-header', '0');
/*!40000 ALTER TABLE `wp_terms` ENABLE KEYS */;
SET FOREIGN_KEY_CHECKS = 1;
SET UNIQUE_CHECKS = 1;