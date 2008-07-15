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

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	// The uuid to retrieve
	$uuid = get_input('uuid');
	
	// Fetch the UUID as an object
	$entity = opendd_fetch_to_elgg($uuid);
	
	// If entity then render
	if ($entity)
		$body = elgg_view_entity($entity, "", true);
	
	$body = elgg_view_layout('one_column',$body);
		
	// Finally draw the page
	page_draw($uuid, $body);
?>