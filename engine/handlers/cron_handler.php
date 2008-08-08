<?php
	/**
	 * Elgg Cron handler.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	// Load Elgg engine
	require_once("../start.php");
	global $CONFIG;
	
	// Get a list of parameters
	$params = array();
	$params['time'] = time();
	
	foreach ($_REQUEST[] as $k => $v)
		$params[$k] = $v;
	
	// Trigger hack
	$std_out = ""; // Data to return to
	$std_out = trigger_plugin_hook('system', 'cron', $params, $std_out);
	
	// Return event
	echo $std_out;
?>