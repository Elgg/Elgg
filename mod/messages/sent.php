<?php

	/**
	 * Elgg sent messages page
	 * 
	 * @package ElggMessages
	 */

	// Load Elgg engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
	// If we're not logged in, forward to the front page
		if (!isloggedin()) forward(); 
		
	// Get the logged in user
		$page_owner = get_loggedin_user();
		set_page_owner($page_owner->guid);
		
	// Get offset
		$offset = get_input('offset',0);
	
	// Set limit
		$limit = 10;
		
    // Display all the messages a user owns, these will make up the sentbox
		// @todo - fix hack where limit + 1 is passed
		$messages = elgg_get_entities_from_metadata(array(
			'metadata_name' => 'fromId',
			'metadata_value' => get_loggedin_userid(),
			'types' => 'object',
			'subtypes' => 'messages',
			'owner_guid' => $page_owner->guid,
			'limit' => $limit + 1,
			'offset' => $offset)
		);
		
    // Set the page title
	    $area2 = elgg_view_title(elgg_echo("messages:sentmessages"));
		
	// Set content
		$area2 .= elgg_view("messages/forms/view",array('entity' => $messages, 'page_view' => "sent", 'limit' => $limit, 'offset' => $offset));

	// Format
		$body = elgg_view_layout("two_column_left_sidebar", '', $area2);
		
	// Draw page
		page_draw(elgg_echo('messages:sentmessages'), $body);
		
?>