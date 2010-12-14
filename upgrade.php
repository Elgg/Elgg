<?php
/**
 * Elgg upgrade script.
 *
 * This script triggers any necessary upgrades. If the site has been upgraded
 * to the most recent version of the code, no upgrades are run and the caches
 * are flushed. If you would prefer that this script is not accessible to others
 * after an upgrade, you can delete it. Future versions of Elgg will include a
 * new version of the script. Deleting the script is not a requirement and
 * leaving it behind does not affect the security of the site.
 *
 * @package Elgg.Core
 * @subpackage Upgrade
 */

define('UPGRADING', 'upgrading');
require_once(dirname(__FILE__) . "/engine/start.php");

if (get_input('upgrade') == 'upgrade') {
	if (version_upgrade_check()) {
		version_upgrade();
	}
	elgg_view_regenerate_simplecache();
	elgg_filepath_cache_reset();
} else {
	echo elgg_view_page(elgg_echo('upgrade'), '', 'upgrade');
	exit;
}

forward();