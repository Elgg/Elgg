<?php
/**
 * Assembles and outputs a login page.
 *
 * This page serves as a fallback for non-JS users who click on the login
 * drop down link.
 *
 * If the user is logged in, this page will forward to the front page.
 */

$title = elgg_echo('login');
$content = elgg_view('core/account/login_box', ['title' => false]);

$shell = elgg_get_config('walled_garden') ? 'walled_garden' : 'default';

$body = elgg_view_layout('default', [
	'content' => $content,
	'title' => $title,
	'sidebar' => false,
]);
echo elgg_view_page($title, $body, $shell);
