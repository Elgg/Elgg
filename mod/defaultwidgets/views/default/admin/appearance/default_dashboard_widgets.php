<?php
/**
 * Elgg default_widgets plugin.
 *
 * @package DefaultWidgets
 * 
 **/

// Set admin user for user block
set_page_owner(get_loggedin_userid());

// create the view
$time = time();
echo elgg_view('defaultwidgets/editor', array(
	'token' => generate_action_token($time),
	'ts' => $time,
	'context' => 'dashboard',
));
