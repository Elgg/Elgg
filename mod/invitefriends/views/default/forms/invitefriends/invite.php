<?php

/**
 * Elgg invite form contents
 *
 * @package ElggInviteFriends
 */
$site = elgg_get_site_entity();
$introduction = elgg_echo('invitefriends:introduction');
$message = elgg_echo('invitefriends:message');
$default = elgg_echo('invitefriends:message:default', array($site->name));
$emails_textarea = elgg_view('input/plaintext', array(
	'id' => 'invitefriends-emails',
	'name' => 'emails',
	'rows' => 4,
		));
$message_textarea = elgg_view('input/plaintext', array(
	'id' => 'invitefriends-emailmessage',
	'name' => 'emailmessage',
	'value' => $default,
		));

$action_button = elgg_view('input/submit', array('value' => elgg_echo('send')));

echo <<< HTML
<div>
	<label for="invitefriends-emails">$introduction</label>
	$emails_textarea
</div>
<div>
	<label for="invitefriends-emailmessage">$message</label>
	$message_textarea
</div>
<div class="elgg-foot">
	$action_button
</div>
HTML;

