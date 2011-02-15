<?php

$params = array(
	'name' => 'name',
);
echo elgg_view('input/text', $params);

echo elgg_view('input/submit', array('value' => elgg_echo('search')));
