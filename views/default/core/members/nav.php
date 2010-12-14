<?php
/**
 * Members navigation
 */

$tabs = array(
	'newest' => array(
		'title' => elgg_echo('members:label:newest'),
		'url' => "pg/members/newest/",
		'selected' => $vars['selected'] == 'newest',
	),
	'popular' => array(
		'title' => elgg_echo('members:label:popular'),
		'url' => "pg/members/popular/",
		'selected' => $vars['selected'] == 'popular',
	),
	'online' => array(
		'title' => elgg_echo('members:label:online'),
		'url' => "pg/members/online/",
		'selected' => $vars['selected'] == 'online',
	),
);

echo elgg_view('navigation/tabs', array('tabs' => $tabs));
