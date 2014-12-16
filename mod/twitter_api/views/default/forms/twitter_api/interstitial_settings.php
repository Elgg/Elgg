<?php
/**
 * Make the user set up some alternative ways to login.
 */

if (elgg_is_sticky_form('twitter_api_interstitial')) {
	$values = elgg_get_sticky_values('twitter_api_interstitial');
	elgg_clear_sticky_form('twitter_api_interstitial');
} else {
	$values = array();
}

$site = elgg_get_site_entity();
$user = elgg_get_logged_in_user_entity();
$display_name = elgg_extract('display_name', $values);
$email = elgg_extract('email', $values);

echo '<div>';
echo elgg_echo('twitter_api:interstitial:description', array($site->name));
echo '</div>';

if (!isset($display_name)) {
	$display_name = $user->name;
}

// username
$title = elgg_echo('username');

$body = elgg_echo('twitter_api:interstitial:username');
$body .= elgg_view('input/text', array('value' => $user->username, 'disabled' => 'disabled'));

echo elgg_view_module('info', $title, $body);

// display name
$title = elgg_echo('name');

$body = elgg_echo('twitter_api:interstitial:name');
$body .= elgg_view('input/text', array('name' => 'display_name', 'value' => $display_name));

echo elgg_view_module('info', $title, $body);

// email
$title = elgg_echo('email');

$body = elgg_echo('twitter_api:interstitial:email');
$body .= elgg_view('input/email', array('name' => 'email', 'value' => $email));

echo elgg_view_module('info', $title, $body);

// password
$title = elgg_echo('password');

$body = elgg_echo('twitter_api:interstitial:password');
$body .= elgg_view('input/password', array('name' => 'password_1'));
$body .= elgg_echo('twitter_api:interstitial:password2');
$body .= elgg_view('input/password', array('name' => 'password_2'));

echo elgg_view_module('info', $title, $body);

// buttons

echo elgg_view('input/submit', array(
	'value' => elgg_echo('save')
));

echo elgg_view('output/url', array(
	'class' => 'float-alt',
	'text' => elgg_echo('twitter_api:interstitial:no_thanks'),
	'href' => '/',
	'is_trusted' => true,
));