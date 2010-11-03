<?php

/**
 * Elgg invite form contents
 *
 * @package ElggInviteFriends
 */

if ($CONFIG->allow_registration) {
	$invite = elgg_echo('friends:invite');
	$introduction = elgg_echo('invitefriends:introduction');
	$message = elgg_echo('invitefriends:message');
	$default = elgg_echo('invitefriends:message:default', array($CONFIG->site->name));

	echo <<< HTML
<h2>$invite</h2>
<p class="margin_top">
	<label>
		$introduction
		<textarea class="input_textarea" name="emails" ></textarea>
	</label>
</p>
<p>
	<label>
		$message
		<textarea class="input_textarea" name="emailmessage" >$default</textarea>
	</label>
</p>
HTML;

	echo elgg_view('input/submit', array('value' => elgg_echo('send')));
} else {
	echo elgg_echo('invitefriends:registration_disabled');
}
