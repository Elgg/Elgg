<?php
/**
 * Elgg 3.0.0 upgrade 2016102600
 * db-engine-to-innodb
 *
 * Converts table engine to innodb
 */

set_time_limit(0);

$db = _elgg_services()->db;

// drop full text indexes
$db->updateData("ALTER TABLE {$db->prefix}groups_entity DROP KEY `name_2`");
$db->updateData("ALTER TABLE {$db->prefix}objects_entity DROP KEY `title`");
$db->updateData("ALTER TABLE {$db->prefix}sites_entity DROP KEY `name`");
$db->updateData("ALTER TABLE {$db->prefix}users_entity DROP KEY `name`, DROP KEY `name_2`");

// update engine
$tables = [
	'access_collection_membership',
	'access_collections',
	'annotations',
	'api_users',
	'config',
	'datalists',
	'entities',
	'entity_relationships',
	'entity_subtypes',
	'groups_entity',
	'metadata',
	'metastrings',
	'objects_entity',
	'private_settings',
	'queue',
	'river',
	'sites_entity',
	'system_log',
	'users_entity',
	'users_remember_me_cookies',
	'users_sessions',
];

foreach ($tables as $table) {
	$db->updateData("ALTER TABLE {$db->prefix}{$table} ENGINE=InnoDB");
}
