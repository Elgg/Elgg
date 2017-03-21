<?php

$params = [
	'name' => 'member_query',
	'class' => 'mbm',
	'required' => true,
];
echo elgg_view('input/text', $params);

echo elgg_view('input/submit', ['value' => elgg_echo('search')]);

echo "<p class='mtl elgg-text-help'>" . elgg_echo('members:total', [get_number_users()]) . "</p>";
