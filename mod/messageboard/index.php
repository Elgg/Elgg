<?php

/**
 * Elgg Message board index page
 *
 * @package ElggMessageBoard
 */


// Load Elgg engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

// Get the user who is the owner of the message board
$entity = elgg_get_page_owner_entity();

// Get any annotations for their message board
$contents = $entity->getAnnotations('messageboard', 50, 0, 'desc');

// Get the content to display
$area2 = elgg_view_title(elgg_echo('messageboard:board'));

// only display the add form and board to logged in users
if (elgg_is_logged_in()) {
	$area2 .= elgg_view("messageboard/forms/add");
	$area2 .= elgg_view("messageboard/messageboard", array('annotation' => $contents));
}


//select the correct canvas area
$body = elgg_view_layout("two_column_left_sidebar", '', $area2);

// Display page
echo elgg_view_page(elgg_echo('messageboard:user', array($entity->name)), $body);

