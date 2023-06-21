<?php

use Elgg\Database\QueryBuilder;

$me = elgg_get_logged_in_user_entity();
echo elgg_view_entity_icon($me);

// show another user if available
$users = elgg_get_entities([
	'type' => 'user',
	'wheres' => [
		function (QueryBuilder $qb, $main_alias) use ($me) {
			return $qb->compare("{$main_alias}.guid", '!=', $me->guid, ELGG_VALUE_GUID);
		},
	],
	'limit' => 1
]);

if (is_array($users) && count($users) > 0) {
	echo elgg_view_entity_icon($users[0]);
}
