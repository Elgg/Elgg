<?php

$params = array(
	'name' => 'member_query',
	'class' => 'mbm',
	'required' => true,
);
echo elgg_view('input/text', $params);

echo elgg_view('input/submit', array('value' => elgg_echo('search')));

echo "<p class='mtl elgg-text-help'>" . elgg_echo('members:total', array(get_number_users())) . "</p>";