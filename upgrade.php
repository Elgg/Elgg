<?php
/**
 * Elgg upgrade script.
 *
 * This script triggers any upgrades necessary, ensuring that upgrades are triggered deliberately by a single
 * user.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

// Include elgg engine
define('upgrading','upgrading');
define('externalpage',true);
require_once(dirname(__FILE__) . "/engine/start.php");

if (get_input('upgrade') == 'upgrade') {
	if (version_upgrade_check()) {
		version_upgrade();
	}
	datalist_set('simplecache_lastupdate',0);

	elgg_filepath_cache_reset();
} else {
	global $CONFIG;
	echo elgg_view('settings/upgrading');
	exit;
}

forward();