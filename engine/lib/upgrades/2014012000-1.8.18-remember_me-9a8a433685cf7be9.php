<?php
/**
 * Elgg 1.8.18 upgrade 2014012000
 *
 * Resets the remember me codes for admin users
 */

$prefix = elgg_get_config('dbprefix');
$query = "
	UPDATE {$prefix}users_entity
	SET `code` = ''
	WHERE `admin` = 'yes'
";
update_data($query);
