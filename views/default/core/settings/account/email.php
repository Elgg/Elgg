<?php
/**
 * Provide a way of setting your email
 *
 * @package Elgg
 * @subpackage Core
 */

$user = elgg_get_page_owner_entity();

if (!$user instanceof ElggUser) {
	return;
}

$title = elgg_echo('email:settings');

$content = '';
if (elgg_get_config('security_email_require_password') && ($user->getGUID() === elgg_get_logged_in_user_guid())) {
	// user needs to provide current password in order to be able to change his/her email address
	$content .= elgg_view_field([
		'#type' => 'password',
		'#label' => elgg_echo('email:address:password'),
		'#help' => elgg_echo('email:address:password:help'),
		'name' => 'email_password',
	]);
}

$content .= elgg_view_field([
	'#type' => 'email',
	'name' => 'email',
	'value' => $user->email,
	'#label' => elgg_echo('email:address:label'),
]);

echo elgg_view_module('info', $title, $content);
