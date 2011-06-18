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

	echo <<< HTML
<div>
	<label>
		$introduction
		<textarea class="elgg-input-textarea" name="emails" ></textarea>
	</label>
</div>
<div>
	<label>
		$message
		<textarea class="elgg-input-textarea" name="emailmessage" >$default</textarea>
	</label>
</div>
HTML;

	echo '<div>';
	echo elgg_view('input/submit', array('value' => elgg_echo('send')));
	echo '</div>';
} else {
	echo elgg_echo('invitefriends:registration_disabled');
}
