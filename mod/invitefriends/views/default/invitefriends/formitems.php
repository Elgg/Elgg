<?php

/**
 * Elgg invite form contents
 *
 * @package ElggInviteFriends
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @link http://elgg.org/
 */

if ($CONFIG->allow_registration) {
	$invite = elgg_echo('friends:invite');
	$introduction = elgg_echo('invitefriends:introduction');
	$message = elgg_echo('invitefriends:message');
	$default = sprintf(elgg_echo('invitefriends:message:default'), $CONFIG->site->name);

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
