<?php
/**
 * Provide a way of setting your email
 *
 * @uses $vars['entity'] the user to set settings for
 */

$user = elgg_extract('entity', $vars, elgg_get_page_owner_entity());
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
		'autocomplete' => 'current-password',
	]);
}

$email_help = '';
if (elgg_get_config('security_email_require_confirmation') && ($new_email = $user->getPrivateSetting('new_email'))) {
	$email_help = elgg_echo('email:address:help:confirm', [$new_email]);
}

$content .= elgg_view_field([
	'#type' => 'email',
	'#label' => elgg_echo('email:address:label'),
	'#help' => $email_help,
	'name' => 'email',
	'value' => $user->email,
]);

if ($user->isAdmin()) {
	// check is unvalidated e-mail notifications are sent
	if ((bool) elgg_get_config('require_admin_validation') && !empty(elgg_get_config('admin_validation_notification'))) {
		$user_setting = $user->getPrivateSetting('admin_validation_notification');
		
		$content .= elgg_view_field([
			'#type' => 'checkbox',
			'#label' => elgg_echo('account:email:admin:validation_notification'),
			'#help' => elgg_echo('account:email:admin:validation_notification:help'),
			'name' => 'admin_validation_notification',
			'value' => 1,
			'checked' => isset($user_setting) ? (bool) $user_setting : true,
			'switch' => true,
		]);
	}
}

echo elgg_view_module('info', $title, $content);
