<?php
	/**
	 * This module pings us on the first install.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Poveysend_api_get_call
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * The api for the pinger.
	 */
	$NOTIFICATION_SERVER = "http://ping.elgg.org/services/api/rest.php";
	
	
	/**
	 * Run once and only once.
	 */
	function ping_run_once()
	{
		global $NOTIFICATION_SERVER, $CONFIG;
	
		include_once($CONFIG->path . "version.php");
		
		// Get export
		$export = export($CONFIG->site_id);
		
		return send_api_post_call($NOTIFICATION_SERVER,
			array(
				'method' => 'elgg.system.ping'
			),
			array(
				'version' => $version,
				'release' => $release,
			),
			$export,
			'text/xml'
		);
	}
	
	/** 
	 * Notify the server.
	 */
	function ping_init()
	{	
		global $CONFIG;
		
		if ($CONFIG->ping_home)
		{
			// Now run this stuff, but only once
			run_function_once("ping_run_once");
		}
	}
	
	// Register a startup event
	register_elgg_event_handler('init','system','ping_init');	
?>