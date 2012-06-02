<?php
/**
 * Walled garden registration
 */

$title = elgg_echo('register');
$body = elgg_view_form('register', array(), array(
	'friend_guid' => (int) get_input('friend_guid', 0),
	'invitecode' => get_input('invitecode'),
));

echo <<<__HTML
<div class="elgg-inner">
	<h2>$title</h2>
	$body
</div>
__HTML;
