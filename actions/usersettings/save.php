<?php
	/**
	 * Aggregate action for saving settings
	 * 
	 * @package Elgg
	 * @subpackage Core


	 * @link http://elgg.org/
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	global $CONFIG;

	gatekeeper();
	action_gatekeeper();
	
	trigger_plugin_hook('usersettings:save','user');
	
	forward($_SERVER['HTTP_REFERER']);
	
?>
