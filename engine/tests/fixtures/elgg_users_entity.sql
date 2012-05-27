CREATE TABLE `elgg_users_entity` (
  `guid` bigint(20) unsigned NOT NULL,
  `name` text NOT NULL,
  `username` varchar(128) NOT NULL DEFAULT '',
  `password` varchar(32) NOT NULL DEFAULT '',
  `salt` varchar(8) NOT NULL DEFAULT '',
  `email` text NOT NULL,
  `language` varchar(6) NOT NULL DEFAULT '',
  `code` varchar(32) NOT NULL DEFAULT '',
  `banned` enum('yes','no') NOT NULL DEFAULT 'no',
  `admin` enum('yes','no') NOT NULL DEFAULT 'no',
  `last_action` int(11) NOT NULL DEFAULT '0',
  `prev_last_action` int(11) NOT NULL DEFAULT '0',
  `last_login` int(11) NOT NULL DEFAULT '0',
  `prev_last_login` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`guid`),
  UNIQUE KEY `username` (`username`),
  KEY `password` (`password`),
  KEY `email` (`email`(50)),
  KEY `code` (`code`),
  KEY `last_action` (`last_action`),
  KEY `last_login` (`last_login`),
  KEY `admin` (`admin`),
  FULLTEXT KEY `name` (`name`),
  FULLTEXT KEY `name_2` (`name`,`username`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `elgg_users_entity` (`guid`,`name`,`username`,`password`,`salt`,`email`,`language`,`code`,`banned`,`admin`,`last_action`,`prev_last_action`,`last_login`,`prev_last_login`) VALUES 
(36,'Test Admin','elgg_test_admin_username','aa5652507c61b3beab9c3547cc58dfc1','a660edaf','admin@quanbit.com','en','','no','yes',0,0,0,0),
(42,'Test User','elgg_test_user_username','928b61ebf02ba396a9ee818870413e27','548a6fa5','user@quanbit.com','en','','no','no',0,0,0,0);