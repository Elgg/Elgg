<?php

// page handler type
$type = $vars['type'];

$username = get_loggedin_user()->username;

// so we know if the user is looking at their own, everyone's or all friends
$filter_context = $vars['context'];

// generate a list of default tabs
$default_tabs = array(
	'all' => array(
		'title' => elgg_echo('all'),
		'url' => (isset($vars['all_link'])) ? $vars['all_link'] : "pg/$type/",
		'selected' => ($filter_context == 'everyone'),
	),
	'mine' => array(
		'title' => elgg_echo('mine'),
		'url' => (isset($vars['mine_link'])) ? $vars['mine_link'] : "pg/$type/$username",
		'selected' => ($filter_context == 'mine'),
	),
	'friend' => array(
		'title' => elgg_echo('friends'),
		'url' => (isset($vars['friend_link'])) ? $vars['friend_link'] : "pg/$type/$username/friends",
		'selected' => ($filter_context == 'friends'),
	),
);

// determine if using default or overwritten tabs
$tabs = elgg_get_array_value('tabs', $vars, $default_tabs);
echo elgg_view('navigation/tabs', array('tabs' => $tabs));