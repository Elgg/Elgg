<?php

/**
 * Elgg Message board history page
 *
 * @package ElggMessageBoard
 */


// Load Elgg engine
require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

// Get the user who is the owner of the message board
$current_user = get_loggedin_userid();

// this is the user how has posted on your messageboard that you want to display your history with
$history_user = get_input('user'); 

$users_array = array($current_user, $history_user);

$contents = get_annotations($users_array, "user", "", "messageboard", $value = "", $users_array, $limit = 10, $offset = 0, $order_by = "desc");

// Get the content to display	
$area2 = elgg_view_title(elgg_echo('messageboard:history:title'));
$area2 .= elgg_view("messageboard/messageboard", array('annotation' => $contents));

//select the correct canvas area
$body = elgg_view_layout("two_column_left_sidebar", '', $area2);

// Display page
page_draw(elgg_echo('messageboard:history:title'),$body);
