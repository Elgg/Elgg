<?php
// newest users
$users = elgg_list_entities([
	'type' => 'user',
	'subtype'=> null,
	'full_view' => false,
]);

echo elgg_view_module('inline', elgg_echo('admin:users:newest'), $users);
