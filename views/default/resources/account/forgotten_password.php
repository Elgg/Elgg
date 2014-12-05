<?php
/**
 * Assembles and outputs the forgotten password page.
 *
 * @package Elgg.Core
 * @subpackage Registration
 */

if (elgg_is_logged_in()) {
	forward();
}

$title = elgg_echo("user:password:lost");

$content = elgg_view_form('user/requestnewpassword', array(
	'class' => 'elgg-form-account',
));

if (elgg_get_config('walled_garden')) {
	elgg_load_css('elgg.walled_garden');
	$body = elgg_view_layout('walled_garden', array('content' => $content));
	echo elgg_view_page($title, $body, 'walled_garden');
} else {
	$body = elgg_view_layout('one_column', array(
		'title' => $title, 
		'content' => $content,
	));
	echo elgg_view_page($title, $body);
}
