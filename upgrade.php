<?php
	/**
	 * Elgg upgrade script.
	 * 
	 * This script triggers any upgrades necessary, ensuring that upgrades are triggered deliberately by a single
	 * user.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
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
		
		$cache = elgg_get_filepath_cache();
		$cache->delete('view_paths');
	} else {
		global $CONFIG;
		echo elgg_view('settings/upgrading');
		exit;
	}
	
	forward();
?>