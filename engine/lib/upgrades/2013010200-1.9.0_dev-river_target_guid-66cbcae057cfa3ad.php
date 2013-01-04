<?php
/**
 * Elgg 1.9.0-dev upgrade 2013010200
 * river_target_guid
 *
 * Adds target_guid field to river table so river items can be saved like
 * "Lisa (subject) posted (action) a comment (object) on John's blog (target)".
 */

$db_prefix = elgg_get_config('dbprefix');
$q1 = "ALTER TABLE {$db_prefix}river ADD target_guid INT(11) NOT NULL AFTER object_guid";
update_data($q1);
$q2 = "ALTER TABLE {$db_prefix}river ADD KEY target_guid (target_guid)";
update_data($q2);
