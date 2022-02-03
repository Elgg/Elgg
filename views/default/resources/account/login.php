<?php
/**
 * Assembles and outputs a login page.
 *
 * This page serves as a fallback for non-JS users who click on the login
 * drop down link.
 *
 * If the user is logged in, this page will forward to the front page.
 */

$shell = elgg_get_config('walled_garden') ? 'walled_garden' : 'default';
$title = elgg_get_session()->has('last_forward_from') ? elgg_echo('login:continue') : elgg_echo('login');

echo elgg_view_page($title, [
	'content' => elgg_view('core/account/login_box', ['title' => false]),
	'sidebar' => false,
	'filter' => false,
], $shell);
