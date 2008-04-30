<?php
	/**
	 * Elgg file browser
	 * 
	 * @package ElggFile
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	
	$limit = get_input("limit", 10);
	$offset = get_input("offset", 0);

	// Get objects of friends
	$owners = array();
	$users = get_entities_from_relationship("friend", page_owner(), false, "", "", 0, "time_created desc", $limit, $offset);
	if ($users)
	{
		foreach ($users as $user)
			$owners[] = $user->getOwner();
		$objects = get_entities("object","file", $owners, "time_created desc", $limit, $offset);
	}

	// Draw page
	$body .= file_draw($objects);

	// Draw footer
	$body .= file_draw_footer($limit, $offset);
	
	// Finally draw the page
	page_draw(sprintf(elgg_echo("file:friends"),$_SESSION['user']->name), $body);
?>