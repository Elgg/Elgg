<?php
/**
 * Elgg user add action
 */

use Elgg\Exceptions\Configuration\RegistrationException;

elgg_make_sticky_form('useradd', ['password', 'password2']);

// Get variables
$username = get_input('username');
$password = get_input('password', null, false);
$password2 = get_input('password2', null, false);
$email = get_input('email');
$name = get_input('name');

// This param is not included in the useradd form by default,
// but it allows sites to easily add the feature if necessary.
$language = get_input('language', elgg_get_config('language'));

$admin = get_input('admin');
$admin = is_array($admin) ? $admin[0] : $admin;

$autogen_password = get_input('autogen_password');
if ($autogen_password) {
	$password = elgg_generate_password();
	$password2 = $password;
}

// no blank fields
if ($username == '' || $password == '' || $password2 == '' || $email == '' || $name == '') {
	return elgg_error_response(elgg_echo('register:fields'));
}

if (strcmp($password, $password2) != 0) {
	return elgg_error_response(elgg_echo('RegistrationException:PasswordMismatch'));
}

// For now, just try and register the user
try {
	$new_user = elgg_register_user([
		'username' => $username,
		'password' => $password,
		'name' => $name,
		'email' => $email,
		'language' => $language,
	]);
	
	if ($admin && elgg_is_admin_logged_in()) {
		$new_user->makeAdmin();
	}

	elgg_clear_sticky_form('useradd');

	$new_user->admin_created = true;
	$new_user->created_by_guid = elgg_get_logged_in_user_guid();

	$subject = elgg_echo('useradd:subject', [], $new_user->getLanguage());
	$body = elgg_echo('useradd:body', [
		elgg_get_site_entity()->getDisplayName(),
		elgg_get_site_entity()->getURL(),
		$username,
		$password,
	], $new_user->getLanguage());

	notify_user($new_user->guid, elgg_get_site_entity()->guid, $subject, $body, [
		'action' => 'useradd',
		'object' => $new_user,
		'password' => $password,
		'apply_muting' => false,
	]);

	return elgg_ok_response('', elgg_echo('adduser:ok', [elgg_get_site_entity()->getDisplayName()]));
} catch (RegistrationException $r) {
	return elgg_error_response($r->getMessage());
}
