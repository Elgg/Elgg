<?php

/**
 * Elgg invite form contents
 *
 * @package ElggInviteFriends
 */

if (elgg_get_config('allow_registration')) {
	$site = elgg_get_site_entity();
	$introduction = elgg_echo('invitefriends:introduction');
	$message = elgg_echo('invitefriends:message');
	$default = elgg_echo('invitefriends:message:default', array($site->name));
	$emails_textarea = elgg_view('input/plaintext', array(
		'name' => 'emails',
		'rows' => 4,
	));
	$message_textarea = elgg_view('input/plaintext', array(
		'name' => 'emailmessage',
		'value' => $default,
	));

	echo <<< HTML
<div>
	<label>
		$introduction
		$emails_textarea
	</label>
</div>
<div>
	<label>
		$message
		$message_textarea
	</label>
</div>
HTML;

	echo '<div class="elgg-foot">';
	echo elgg_view('input/submit', array('value' => elgg_echo('send')));
	echo '</div>';
} else {
	echo elgg_echo('invitefriends:registration_disabled');
}
