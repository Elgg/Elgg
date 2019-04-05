<?php
/**
 * Assembles and outputs the forgotten password page.
 */

$title = elgg_echo('user:password:lost');

$hash_missing_username = elgg_get_session()->get('forgotpassword:hash_missing');
if ($hash_missing_username) {
	elgg_get_session()->remove('forgotpassword:hash_missing');
	register_error(elgg_echo('user:password:hash_missing'));
}

$form_vars = ['class' => 'elgg-form-account'];
$body_vars = ['username' => $hash_missing_username];
$content = elgg_view_form('user/requestnewpassword', $form_vars, $body_vars);

$shell = elgg_get_config('walled_garden') ? 'walled_garden' : 'default';

$body = elgg_view_layout('default', [
	'content' => $content,
	'title' => $title,
	'sidebar' => false,
]);
echo elgg_view_page($title, $body, $shell);
