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
	
	$input = array();
	foreach($CONFIG->opendd as $shortname => $valuetype) {
		$input[$shortname] = get_input($shortname);
		if ($valuetype == 'tags')
			$input[$shortname] = string_to_tag_array($input[$shortname]);
	}
	
	$feed_guid = get_input('feed_guid');
	$feed = NULL;
	if ($feed_guid)
		$feed = get_entity($feed_guid);
	else
	{
		$feed = new ElggObject();
		$feed->subtype = 'oddfeed';
	}
	
	if (!($feed instanceof ElggObject))
	{
		register_error(elgg_echo("opendd:notobject"));
		
		forward($_SERVER['HTTP_REFERER']);
		exit;
	}
	
	// Assume we can edit or this is a new group
	if (sizeof($input) > 0)
	{
		foreach($input as $shortname => $value) {
			$feed->$shortname = $value;
		}
	}
	
	
	if ($feed->save())
		system_message(elgg_echo("opendd:feedok"));
	else
		register_error(elgg_echo("opendd:feednotok"));
	
	forward($_SERVER['HTTP_REFERER']);
	exit;
?>