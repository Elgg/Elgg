<?php
/**
 * Elgg register form
 *
 * @package Elgg
 * @subpackage Core
 */

$register_url = elgg_get_site_url();
if ((isset($CONFIG->https_login)) && ($CONFIG->https_login)) {
	$register_url = str_replace("http:", "https:", $register_url);
}

$username = get_input('u');
$email = get_input('e');
$name = get_input('n');

$admin_option = false;
$loggedin_user = get_loggedin_user();

if ($loggedin_user && $loggedin_user->isAdmin() && isset($vars['show_admin'])) {
	$admin_option = true;
}

$form_body  = "<p><label>" . elgg_echo('name') . "<br />" . elgg_view('input/text' , array('internalname' => 'name', 'class' => "input_text name", 'value' => $name)) . "</label></p>";
$form_body .= "<p><label>" . elgg_echo('email') . "<br />" . elgg_view('input/text' , array('internalname' => 'email', 'class' => "input_text", 'value' => $email)) . "</label></p>";
$form_body .= "<p><label>" . elgg_echo('username') . "<br />" . elgg_view('input/text' , array('internalname' => 'username', 'class' => "input_text", 'value' => $username)) . "</label></p>";
$form_body .= "<p><label>" . elgg_echo('password') . "<br />" . elgg_view('input/password' , array('internalname' => 'password', 'class' => "input_password")) . "</label></p>";
$form_body .= "<p><label>" . elgg_echo('passwordagain') . "<br />" . elgg_view('input/password' , array('internalname' => 'password2', 'class' => "input_password")) . "</label></p>";

// view to extend to add more fields to the registration form
$form_body .= elgg_view('register/extend');

// Add captcha hook
$form_body .= elgg_view('input/captcha');

if ($admin_option) {
	$form_body .= elgg_view('input/checkboxes', array('internalname' => "admin", 'options' => array(elgg_echo('admin_option'))));
}

$form_body .= elgg_view('input/hidden', array('internalname' => 'friend_guid', 'value' => $vars['friend_guid']));
$form_body .= elgg_view('input/hidden', array('internalname' => 'invitecode', 'value' => $vars['invitecode']));
$form_body .= elgg_view('input/hidden', array('internalname' => 'action', 'value' => 'register'));
$form_body .= elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('register'))) . "</p>";

echo elgg_view('input/form', array(
	'action' => "{$login_url}action/register",
	'body' => $form_body,
	'class' => "margin_top"
));
?>

<script type="text/javascript">
	$(document).ready(function() { $('input[name=name]').focus(); });
</script>
