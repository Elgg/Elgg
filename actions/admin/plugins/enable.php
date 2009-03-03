<?php
	/**
	 * Enable plugin action.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");
	
	// block non-admin users
	admin_gatekeeper();
	
	// Validate the action
	action_gatekeeper();
	
	// Get the plugin 
	$plugin = get_input('plugin');
	if (!is_array($plugin))
		$plugin = array($plugin);
	
	foreach ($plugin as $p)
	{
		// Disable
		if (enable_plugin($p))
			system_message(sprintf(elgg_echo('admin:plugins:enable:yes'), $p));
		else
			register_error(sprintf(elgg_echo('admin:plugins:enable:no'), $p));		
	}
		
	elgg_view_regenerate_simplecache();
	
	$cache = elgg_get_filepath_cache();
	$cache->delete('view_paths');
	
	forward($_SERVER['HTTP_REFERER']);
	exit;
?>