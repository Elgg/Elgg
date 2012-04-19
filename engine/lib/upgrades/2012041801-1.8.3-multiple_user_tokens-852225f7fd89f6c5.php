<?php
/**
 * Elgg 1.8.3 upgrade 2012041801
 * multiple_user_tokens
 *
 * Fixes http://trac.elgg.org/ticket/4291
 * Removes the unique index on users_apisessions for user_guid and site_guid
 */

$db_prefix = elgg_get_config('dbprefix');
$q = "ALTER TABLE {$db_prefix}users_apisessions DROP INDEX user_guid,
	ADD INDEX user_guid (user_guid, site_guid)";
update_data($q);