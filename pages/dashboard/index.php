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
set_page_owner(get_loggedin_userid());
$title = elgg_echo('dashboard');

// wrap intro message in a div
$intro_message = elgg_view('dashboard/blurb');

// Try and get the user from the username and set the page body accordingly
$body = elgg_view_layout('widgets', "", "", $intro_message);

echo elgg_view_page($title, $body);