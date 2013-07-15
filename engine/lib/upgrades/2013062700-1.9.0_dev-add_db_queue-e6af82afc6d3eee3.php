<?php
/**
 * Elgg 1.9.0-dev upgrade 2013062700
 * add_db_queue
 *
 * Creates the table for queue support
 */

$db_prefix = elgg_get_config('dbprefix');

// create queue table
$query = <<<SQL
CREATE TABLE IF NOT EXISTS `{$db_prefix}queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `data` mediumblob NOT NULL,
  `timestamp` int(11) NOT NULL,
  `worker` varchar(32) NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `retrieve` (`timestamp`,`worker`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
SQL;
update_data($query);
