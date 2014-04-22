<?php
/**
 * Elgg 1.8.18 upgrade 2014012000
 *
 * Resets the remember me codes for admin users
 */

$prefix = elgg_get_config('dbprefix');
$query = "
	DELETE FROM {$prefix}users_remember_me_cookies
	WHERE guid IN (
		SELECT guid
		FROM {$prefix}users_entity
		WHERE admin = 'yes'
	)
";
update_data($query);
