<?php
	/**
	 * Elgg OpenDD aggregator
	 * 
	 * @package ElggOpenDD
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */	

	// Load configuration
	global $CONFIG;
	
	gatekeeper();
	
	$returnurl = $_SERVER['HTTP_REFERER'];
	
	$feed_guid = get_input('feed_guid');
	$feed = NULL;
	if ($feed_guid)
		$feed = get_entity($feed_guid);
	
	if (($feed) || (!($feed instanceof ElggObject)))
	{
		if ($feed->delete()) {
			system_message(elgg_echo("opendd:deleted"));
			$returnurl = $CONFIG->url . "mod/opendd/";
		}
		else
			system_message(elgg_echo("opendd:notdeleted"));
		
	}
	else
		system_message(elgg_echo("opendd:notobject"));

		
		
	$returnurl = $CONFIG->url . "mod/opendd/";
	forward($returnurl);
	exit;
?>