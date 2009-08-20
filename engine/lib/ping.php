<?php
	/**
	 * This module pings us on the first install.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @author Curverider Ltdsend_api_get_call
	 * @link http://elgg.org/
	 */

	/**
	 * The api for the pinger.
	 * TODO: Have this configurable and/or updatable
	 */
	$NOTIFICATION_SERVER = "http://ping.elgg.org/pg/api/rest/php/";
	
	
	/**
	 * Run once and only once.
	 * 
	 * @param ElggSite $site The site who's information to use
	 */
	function ping_home(ElggSite $site)
	{
		global $NOTIFICATION_SERVER, $CONFIG;
	
		// Get version information
		$version = get_version();
		$release = get_version(true);
		
		// Get export
		$export = export($site->guid);
		
		return send_api_post_call($NOTIFICATION_SERVER,
			array(
				'method' => 'elgg.system.ping',
			
				'url'	  => $site->url,
				'version' => $version,
				'release' => $release,
			),
			array(),
			$export,
			'text/xml'
		);
	}
?>