<?php
echo '<h3>Single value selection</h3>';

echo elgg_view('input/autocomplete', array(
	'name' => 'test1',
	'match_on' => 'all',
	'placeholder' => 'Input any user\'s or group name',
));

echo elgg_view('input/autocomplete', array(
	'name' => 'test2',
	'match_on' => 'users',
	'placeholder' => 'Input any user\'s name',
));

echo elgg_view('input/autocomplete', array(
	'name' => 'test3',
	'match_on' => 'groups',
	'placeholder' => 'Input group\'s name',
));

echo elgg_view('input/autocomplete', array(
	'name' => 'test4',
	'match_on' => 'friends',
	'placeholder' => 'Input your friend\'s name',
));


echo '<h3>Multiple values selection</h3>';

echo elgg_view('input/autocomplete', array(
	'name' => 'test1',
	'match_on' => 'all',
	'multiple' => true,
	'placeholder' => 'Input any user\'s or group name',
));

echo elgg_view('input/autocomplete', array(
	'name' => 'test2',
	'match_on' => 'users',
	'multiple' => true,
	'placeholder' => 'Input any user\'s name',
));

echo elgg_view('input/autocomplete', array(
	'name' => 'test3',
	'match_on' => 'groups',
	'multiple' => true,
	'placeholder' => 'Input group\'s name',
));

echo elgg_view('input/autocomplete', array(
	'name' => 'test4',
	'match_on' => 'friends',
	'multiple' => true,
	'placeholder' => 'Input your friend\'s name',
));

