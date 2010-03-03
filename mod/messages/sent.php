<?php

	/**
	 * Elgg sent messages page
	 * 
	 * @package ElggMessages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	// Load Elgg engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
	// If we're not logged in, forward to the front page
		if (!isloggedin()) forward(); 
		
	// Get the logged in user
		$page_owner = $_SESSION['user'];
		set_page_owner($page_owner->guid);
		
	// Get offset
		$offset = get_input('offset',0);
	
	// Set limit
		$limit = 10;
		
    // Display all the messages a user owns, these will make up the sentbox
		$messages = elgg_get_entities_from_metadata(array('metadata_name' => 'fromId', 'metadata_value' => $_SESSION['user']->guid, 'types' => 'object', 'subtypes' => 'messages', 'owner_guid' => $page_owner->guid, 'limit' => $limit, 'offset' => $offset)); 
		//$page_owner->getObjects('messages');
		
    // Set the page title
	    $area2 = elgg_view_title(elgg_echo("messages:sentmessages"));
		
	// Set content
		// $area2 .= elgg_view("messages/view",array('entity' => $messages, 'page_view' => "sent", 'limit' => $limit, 'offset' => $offset));
		$area2 .= elgg_view("messages/forms/view",array('entity' => $messages, 'page_view' => "sent", 'limit' => $limit, 'offset' => $offset));

	// Format
		$body = elgg_view_layout("two_column_left_sidebar", '', $area2);
		
	// Draw page
		page_draw(sprintf(elgg_echo('messages:sentMessages'),$page_owner->name),$body);
		
?>