CREATE TABLE `wp_users` (  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,  `user_login` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',  `user_pass` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',  `user_nicename` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',  `user_email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',  `user_url` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',  `user_activation_key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',  `user_status` int(11) NOT NULL DEFAULT '0',  `display_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',  PRIMARY KEY (`ID`),  KEY `user_login_key` (`user_login`),  KEY `user_nicename` (`user_nicename`),  KEY `user_email` (`user_email`)) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40000 ALTER TABLE `wp_users` DISABLE KEYS */;
SET FOREIGN_KEY_CHECKS = 0;
SET UNIQUE_CHECKS = 0;
INSERT INTO `wp_users` VALUES('1', 'orange', '$P$BfxSqkiCQroNchC5a.ChdGyVqWnRg8/', 'orange', '33evgenyy33@gmail.com', '', '2018-01-29 15:32:33', '', '0', 'orange');
INSERT INTO `wp_users` VALUES('2', 'infogsim@gmail.com', '$P$BenyzQf1a19mRW51/h2UE8dsNUziah1', 'infogsim-gmail-com', 'infogsim@gmail.com', '', '2018-02-12 11:42:47', '', '0', 'Имя');
INSERT INTO `wp_users` VALUES('3', 'e.baulina@euroroaming.ru', '$P$B9.n6gnJCSiPUkVlKaiT/wD7n5PYGX1', 'e-baulina-euroroaming-ru', 'e.baulina@euroroaming.ru', '', '2018-03-26 07:34:46', '', '0', 'Елена Баулина');
INSERT INTO `wp_users` VALUES('4', 'MNefedov', '$P$BBjEg9t/41.TdzcArgIeCKYaaalsws.', 'mnefedov', 'm.nefedov@sgsim.ru', '', '2018-03-29 08:38:56', '', '0', 'Михаил Нефедов');
INSERT INTO `wp_users` VALUES('5', 'm.prohorova@euroroaming.ru', '$P$Bz/BUq6W6yRFAi.2My.STDd.jY5Ea3/', 'm-prohorova-euroroaming-ru', 'm.prohorova@euroroaming.ru', '', '2018-04-09 14:47:18', '', '0', 'Мария Прохорова');
INSERT INTO `wp_users` VALUES('6', 'e.simakova@euroroaming.ru', '$P$BjfWwkqzsUVqQCg7q5nDGU7eMAjeNs0', 'e-simakova-euroroaming-ru', 'e.simakova@euroroaming.ru', '', '2018-04-09 14:48:17', '', '0', 'Екатерина Симакова');
INSERT INTO `wp_users` VALUES('7', 'e.kobrisova@euroroaming.ru', '$P$BV4r3l90Luekk9uyNyogI2pkA625Yq.', 'e-kobrisova-euroroaming-ru', 'e.kobrisova@euroroaming.ru', '', '2018-04-09 15:00:12', '', '0', 'Елена Кобрисова');
INSERT INTO `wp_users` VALUES('8', 'i.stupina@euroroaming.ru', '$P$B/yxs7hsEInDdBpVBDRUln.WmpGfG80', 'i-stupina-euroroaming-ru', 'i.stupina@euroroaming.ru', '', '2018-04-09 15:10:35', '', '0', 'Ирина Ступина');
INSERT INTO `wp_users` VALUES('9', 'o.koshkina@euroroaming.ru', '$P$BfNgIVzyM9wxx/2BHqRsguOiiVGp6y/', 'o-koshkina-euroroaming-ru', 'o.koshkina@euroroaming.ru', '', '2018-04-09 15:12:03', '', '0', 'Ольга Кошкина');
INSERT INTO `wp_users` VALUES('10', 'a.shalashov@euroroaming.ru', '$P$BvWzDjlL03626201AvAmW55UtC62Ld.', 'a-shalashov-euroroaming-ru', 'a.shalashov@euroroaming.ru', '', '2018-04-11 09:24:47', '', '0', 'Алексей Шалашов');
/*!40000 ALTER TABLE `wp_users` ENABLE KEYS */;
SET FOREIGN_KEY_CHECKS = 1;
SET UNIQUE_CHECKS = 1;