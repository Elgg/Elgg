<?php
/**
 * Elgg default_widgets plugin.
 *
 * @package DefaultWidgets
 * 
 **/

// set admin user for user block
set_page_owner($_SESSION['guid']);

// create the view
$time = time();
echo elgg_view('defaultwidgets/editor', array (
	'token' => generate_action_token($time),
	'ts' => $time,
	'context' => 'profile',
));
