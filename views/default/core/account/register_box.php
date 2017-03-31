<?php
/**
 * Elgg register box
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['module'] The module name. Default: aside
 */

$module = elgg_extract('module', $vars, 'aside');
unset($vars['module']);

$title = elgg_extract('title', $vars, elgg_echo('register'));
unset($vars['title']);

$friend_guid = (int) get_input('friend_guid', 0);
$invitecode = get_input('invitecode');

$form_params = [
	'class' => 'elgg-form-account card-block',
];

$body_params = [
	'friend_guid' => $friend_guid,
	'invitecode' => $invitecode
];

$content = elgg_view_form('register', $form_params, $body_params);

$vars['class'] = elgg_extract_class($vars, 'elgg-register-box');
$vars['footer'] = elgg_view('help/register');

echo elgg_view_module($module, $title, $content, $vars);
