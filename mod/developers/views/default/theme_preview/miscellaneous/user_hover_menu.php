<?php

$me = elgg_get_logged_in_user_entity();
echo elgg_view_entity_icon($me);

// show another user if available
$users = elgg_get_entities(array(
	'type' => 'user',
	'wheres' => array("guid != {$me->getGUID()}"),
	'limit' => 1
));

if (is_array($users) && count($users) > 0) {
	echo elgg_view_entity_icon($users[0]);
}

