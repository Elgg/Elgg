<?php
/**
 * Page for resetting a forgotten password
 */

$user_guid = get_input('u');

$user = get_user($user_guid);

// don't check code here to avoid automated attacks
if (!$user instanceof ElggUser) {
	throw new \Elgg\EntityNotFoundException(elgg_echo('user:changepassword:unknown_user'));
}

$content = elgg_view_form('user/changepassword', [
	'class' => 'elgg-form-account',
	'prevent_double_submit' => true,
], [
	'guid' => $user_guid,
	'code' => get_input('c'),
]);

$shell = elgg_get_config('walled_garden') ? 'walled_garden' : 'default';

echo elgg_view_page(elgg_echo('changepassword'), [
	'content' => $content,
	'sidebar' => false,
], $shell);
