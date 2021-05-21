<?php
/**
 * Assembles and outputs the registration page.
 *
 * Since 1.8, registration can be disabled via administration.  If this is
 * the case, calls to this page will forward to the network front page.
 *
 * If the user is logged in, this page will forward to the network
 * front page.
 */

use Elgg\Exceptions\Http\GatekeeperException;

// check new registration allowed
if (elgg_get_config('allow_registration') == false) {
	throw new GatekeeperException(elgg_echo('registerdisabled'));
}

$friend_guid = (int) get_input('friend_guid', 0);
$invitecode = get_input('invitecode');

$form_params = [
	'class' => 'elgg-form-account',
	'ajax' => true,
];

$body_params = [
	'friend_guid' => $friend_guid,
	'invitecode' => $invitecode
];
$content = elgg_view_form('register', $form_params, $body_params);

$content .= elgg_view('help/register');

$shell = elgg_get_config('walled_garden') ? 'walled_garden' : 'default';

echo elgg_view_page(elgg_echo('register'), [
	'content' => $content,
	'sidebar' => false,
	'filter' => false,
], $shell);
