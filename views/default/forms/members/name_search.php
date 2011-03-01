<?php

$params = array(
	'name' => 'name',
	'class' => 'mbm',
);
echo elgg_view('input/text', $params);

echo elgg_view('input/submit', array('value' => elgg_echo('search')));
