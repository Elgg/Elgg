<?php
/**
 * Elgg 1.9.0-rc.3 upgrade 2014070600
 * river_enabled_col
 *
 * Add an 'enabled' column to the river table
 */

$dbprefix = elgg_get_config('dbprefix');

$q1 = "ALTER TABLE  {$dbprefix}river ADD  enabled ENUM(  'yes',  'no' ) NOT NULL DEFAULT  'yes';";
update_data($q1);

// update any river entries that need to be disabled
$q2 = <<<Q2
	UPDATE {$dbprefix}river AS rv
	LEFT JOIN {$dbprefix}entities AS se ON se.guid = rv.subject_guid
	LEFT JOIN {$dbprefix}entities AS te ON te.guid = rv.target_guid
	LEFT JOIN {$dbprefix}entities AS oe ON oe.guid = rv.object_guid
	SET rv.enabled = 'no'
	WHERE (se.enabled = 'no' OR te.enabled = 'no' OR oe.enabled = 'no');
Q2;

update_data($q2);