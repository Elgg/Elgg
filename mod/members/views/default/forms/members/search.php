<?php

$params = array(
	'name' => 'member_query',
	'class' => 'mbm',
);
echo elgg_view('input/text', $params);

echo elgg_view('input/submit', array('value' => elgg_echo('search')));
