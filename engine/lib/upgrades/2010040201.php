<?php

/**
 * Pull admin metadata setting into users_entity table column
 */

$siteadmin = add_metastring('siteadmin');
$admin = add_metastring('admin');
$yes = add_metastring('yes');
$one = add_metastring('1');

$qs = array();

$qs[] = "ALTER TABLE {$CONFIG->dbprefix}users_entity DISABLE KEYS";

$qs[] = "ALTER TABLE {$CONFIG->dbprefix}users_entity
	ADD admin ENUM('yes', 'no') NOT NULL DEFAULT 'no' AFTER `banned`";

$qs[] = "UPDATE {$CONFIG->dbprefix}users_entity SET admin = 'yes' where guid IN (select x.guid FROM(
SELECT * FROM {$CONFIG->dbprefix}users_entity as e,
	{$CONFIG->dbprefix}metadata as md
	WHERE (
		md.name_id IN ('$admin', '$siteadmin')
		AND md.value_id IN ('$yes', '$one')
		AND e.guid = md.entity_guid
		AND e.banned = 'no'
	)) as x)";

$qs[] = "ALTER TABLE {$CONFIG->dbprefix}users_entity ADD KEY admin (admin)";

$qs[] = "ALTER TABLE {$CONFIG->dbprefix}users_entity ENABLE KEYS";

$qs[] = "DELETE FROM {$CONFIG->dbprefix}metadata
	WHERE (
		name_id IN ('$admin', '$siteadmin')
		AND value_id IN ('$yes', '$one')
	)";

foreach ($qs as $q) {
	update_data($q);
}
