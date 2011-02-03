<?php
/**
 * Elgg dashboard
 *
 * @package Elgg
 * @subpackage Core
 */

// Ensure that only logged-in users can see this page
gatekeeper();

// Set context and title
elgg_set_context('dashboard');
elgg_set_page_owner_guid(get_loggedin_userid());
$title = elgg_echo('dashboard');

// wrap intro message in a div
$intro_message = elgg_view('core/dashboard/blurb');

$params = array(
	'content' => $intro_message,
	'num_columns' => 3,
	'show_access' => false,
);
$widgets = elgg_view_layout('widgets', $params);

$body = elgg_view_layout('one_column', array('content' => $widgets));

echo elgg_view_page($title, $body);