<?php
/**
 * Elgg 3.0.0 upgrade 2016102500
 * remove_site_guid
 */

$db = _elgg_services()->db;

// validate if multiple sites are in the database
$tables = ['access_collections', 'api_users', 'config', 'entities', 'users_apisessions'];
foreach ($tables as $table) {
	$row = $db->getDataRow("
		SELECT count(DISTINCT site_guid) as count FROM {$db->prefix}{$table}
	");
	
	if ((int) $row->count > 1) {
		throw new \DatabaseException("Multiple sites detected in table: '{$db->prefix}{$table}'. Can't upgrade the database.");
	}
}

// remove site guid from access_collections
$db->updateData("
	ALTER TABLE {$db->prefix}access_collections
	DROP KEY site_guid,
	DROP COLUMN site_guid
");

// remove site guid from api_users
$db->updateData("
	ALTER TABLE {$db->prefix}api_users
	DROP COLUMN site_guid
");

// remove site guid from config
$db->updateData("
	ALTER TABLE {$db->prefix}config
	DROP PRIMARY KEY, ADD PRIMARY KEY(name),
	DROP COLUMN site_guid
");

// remove site guid from entities
$db->updateData("
	ALTER TABLE {$db->prefix}entities
	DROP KEY site_guid,
	DROP COLUMN site_guid
");

// remove site guid from users_apisessions
$db->updateData("
	ALTER TABLE {$db->prefix}users_apisessions
	DROP KEY user_guid,
	DROP COLUMN site_guid,
	ADD KEY(user_guid)
");
