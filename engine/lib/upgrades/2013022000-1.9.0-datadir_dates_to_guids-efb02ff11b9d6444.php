<?php
/**
 * Elgg 1.9.0 upgrade 2013022000
 * datadir_dates_to_guids
 *
 * Rewrites user directories in data directory to use guids instead of creation dates
 */


$migrate_link = elgg_view('output/url', array(
	'href' => 'admin/upgrades/datadirs',
	'text' => "migrate the user directories",
	'is_trusted' => true,
));

// not using translation because new keys won't be in the cache
elgg_add_admin_notice('datadirs_upgrade_needed', "The file storage structure for users has changed in Elgg 1.9. You must $migrate_link.");
