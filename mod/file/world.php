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
	$tag = get_input("tag");
	
	// Get objects
	if ($tag!="")
		$objects = get_entities_from_metadata("tag", $tag, "object", "file", $limit, $offset);
	else
		$objects = get_entities("object","file", "", "time_created desc", $limit, $offset);

	// Draw page
	$body .= file_draw($objects);

	// Draw footer
	$body .= file_draw_footer($limit, $offset);
	
	// Finally draw the page
	page_draw(sprintf(elgg_echo("file:yours"),$_SESSION['user']->name), $body);
?>