<?php
/**
 * Walled garden registration
 */

$title = elgg_echo('register');
$body = elgg_view_form('register', array(), array(
	'friend_guid' => (int) get_input('friend_guid', 0),
	'invitecode' => get_input('invitecode'),
));

$content = <<<__HTML
<div class="elgg-inner">
	<h2>$title</h2>
	$body
</div>
__HTML;

echo elgg_view_module('walledgarden', '', $content, array(
	'class' => 'elgg-walledgarden-single elgg-walledgarden-register hidden',
	'header' => ' ',
	'footer' => ' ',
));