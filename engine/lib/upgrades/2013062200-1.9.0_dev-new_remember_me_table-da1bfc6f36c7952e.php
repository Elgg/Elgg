<?php
/**
 * Elgg 1.9.0-dev upgrade 2013062200
 * new_remember_me_table
 *
 * Moves the remember code into the new table and then drops the code from
 * the users entity table
 */

$db_prefix = elgg_get_config('dbprefix');

// create remember me table
$query1 = <<<SQL
	CREATE TABLE IF NOT EXISTS `{$db_prefix}users_remember_me_cookies` (
	  `code` varchar(32) NOT NULL,
	  `guid` bigint(20) unsigned NOT NULL,
	  `timestamp` int(11) unsigned NOT NULL,
	  PRIMARY KEY (`code`),
	  KEY `timestamp` (`timestamp`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SQL;
update_data($query1);

// move codes
$time = time();
$query2 = <<<SQL
	INSERT INTO {$db_prefix}users_remember_me_cookies (`code`, `guid`, `timestamp`)
	SELECT `code`, `guid`, $time
	FROM {$db_prefix}users_entity
	WHERE `code` != ''
SQL;
update_data($query2);

// drop code from users table
$query3 = "ALTER TABLE {$db_prefix}users_entity DROP `code`";
update_data($query3);
