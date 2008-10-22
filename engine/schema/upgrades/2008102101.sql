
CREATE TABLE  IF NOT EXISTS `prefix_users_sessions` (
	`id` int(11) NOT NULL auto_increment,
	`session` varchar(255) NOT NULL,
 	`ts` int(11) unsigned NOT NULL default '0',
	`data` mediumblob,
	
	PRIMARY KEY (`id`),
	UNIQUE KEY `session` (`session`),
	KEY `ts` (`ts`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;