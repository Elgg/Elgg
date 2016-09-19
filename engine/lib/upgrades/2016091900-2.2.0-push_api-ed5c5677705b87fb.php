<?php
/**
 * Elgg 2.2.0 upgrade 2016091900
 * push_api
 *
 * Create tables for Push API channels
 */

// upgrade code here.
$db = _elgg_services()->db;

$db->updateData("
	CREATE TABLE IF NOT EXISTS {$db->prefix}channels (
	  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	  `name` varchar(255) NOT NULL,
	  `retire_if_unused` tinyint(1) NOT NULL DEFAULT 0,
	  PRIMARY KEY (`id`),
	  UNIQUE KEY `name` (`name`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$db->updateData("
	CREATE TABLE IF NOT EXISTS {$db->prefix}channel_messages (
	  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
	  `channel_id` bigint(20) unsigned NOT NULL,
	  `data` TEXT NOT NULL,
	  `time_created` int(11) NOT NULL,
	  PRIMARY KEY (`id`),
	  KEY `channel_id` (`channel_id`)
	) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");
