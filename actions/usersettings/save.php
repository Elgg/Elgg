<?php
	/**
	 * Aggregate action for saving settings
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	global $CONFIG;

	gatekeeper();
	action_gatekeeper();
	
	trigger_plugin_hook('usersettings:save','user');
	
	forward($_SERVER['HTTP_REFERER']);
	
?>
