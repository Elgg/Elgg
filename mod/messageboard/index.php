<?php

/**
 * Elgg Message board index page
 *
 * @package ElggMessageBoard
 */


// Load Elgg engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

// Get the user who is the owner of the message board
$entity = get_entity(page_owner());

// Get any annotations for their message board
$contents = $entity->getAnnotations('messageboard', 50, 0, 'desc');

// Get the content to display
$area2 = elgg_view_title(elgg_echo('messageboard:board'));

// only display the add form to logged in users
if (isloggedin()) {
	$area2 .= elgg_view("messageboard/forms/add");
}

$area2 .= elgg_view("messageboard/messageboard", array('annotation' => $contents));

//select the correct canvas area
$body = elgg_view_layout("two_column_left_sidebar", '', $area2);

// Display page
page_draw(sprintf(elgg_echo('messageboard:user'),$entity->name),$body);

