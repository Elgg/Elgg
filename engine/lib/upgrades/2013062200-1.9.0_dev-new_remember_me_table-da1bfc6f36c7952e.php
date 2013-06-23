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
$ia = elgg_set_ignore_access(true);
$options = array(
	'type' => 'user',
	'limit' => 0,
	'selects' => array("u.code as code"),
	'joins' => array("JOIN {$db_prefix}users_entity u ON e.guid = u.guid"),
);
$batch = new ElggBatch('elgg_get_entities', $options);
foreach ($batch as $entity) {
	$code = $entity->getVolatileData('select:code');
	if ($code) {
		_elgg_add_remember_me_cookie($entity, $code);
	}
}
elgg_set_ignore_access($ia);

// drop code from users table
$query2 = "ALTER TABLE {$db_prefix}users_entity DROP code";
update_data($query2);
