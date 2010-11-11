<?php

	/**
	 * Elgg thewire index page
	 *
	 * @package Elggthewire
	 */

	// Load Elgg engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	// Get the current page's owner
		$page_owner = elgg_get_page_owner();
		if ($page_owner === false || is_null($page_owner)) {
			$page_owner = get_loggedin_user();
			set_page_owner($page_owner->getGUID());
		}

	// title
		if (elgg_get_page_owner_guid() == get_loggedin_userid()) {
			$area2 = elgg_view_title(elgg_echo("thewire:read"));
		} else {
			$area2 = elgg_view_title(elgg_echo("thewire:user",array($page_owner->name)));
		}

	//add form
		$area2 .= elgg_view("thewire/forms/add");

	// Display the user's wire
		$area2 .= list_user_objects($page_owner->getGUID(),'thewire');

	//select the correct canvas area
		$body = elgg_view_layout("one_column_with_sidebar", array('content' => $area2));

	// Display page
		echo elgg_view_page(elgg_echo('thewire:user', array($page_owner->name)), $body);

?>