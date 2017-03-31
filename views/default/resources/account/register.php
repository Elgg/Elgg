<?php
/**
 * Assembles and outputs the registration page.
 *
 * Since 1.8, registration can be disabled via administration.  If this is
 * the case, calls to this page will forward to the network front page.
 *
 * If the user is logged in, this page will forward to the network
 * front page.
 *
 * @package Elgg.Core
 * @subpackage Registration
 */

// check new registration allowed
if (elgg_get_config('allow_registration') == false) {
	register_error(elgg_echo('registerdisabled'));
	forward();
}

// only logged out people need to register
if (elgg_is_logged_in()) {
	forward();
}

$content = elgg_view('core/account/register_box', [
	'class' => 'card',
	'title' => '',
]);

$shell = elgg_get_config('walled_garden') ? 'walled_garden' : 'default';

$body = elgg_view_layout('default', [
	'content' => $content,
	'title' => elgg_echo('register'),
	'sidebar' => false,
]);
echo elgg_view_page($title, $body, $shell);
