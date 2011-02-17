<?php
/**
 * Main content filter
 *
 * Select between user, friends, and all content
 *
 * @uses $vars['filter_context']  Filter context: all, friends, mine
 * @uses $vars['filter_override'] HTML for overriding the default filter (override)
 * @uses $vars['context']         Page context (override)
 */

if (isset($vars['filter_override'])) {
	echo $vars['filter_override'];
	return true;
}

$context = elgg_extract('context', $vars, elgg_get_context());

if (elgg_is_logged_in() && $context) {
	$username = elgg_get_logged_in_user_entity()->username;
	$filter_context = elgg_extract('filter_context', $vars, 'all');

	// generate a list of default tabs
	$tabs = array(
		'all' => array(
			'title' => elgg_echo('all'),
			'url' => (isset($vars['all_link'])) ? $vars['all_link'] : "pg/$context/all/",
			'selected' => ($filter_context == 'all'),
		),
		'mine' => array(
			'title' => elgg_echo('mine'),
			'url' => (isset($vars['mine_link'])) ? $vars['mine_link'] : "pg/$context/owner/$username/",
			'selected' => ($filter_context == 'mine'),
		),
		'friend' => array(
			'title' => elgg_echo('friends'),
			'url' => (isset($vars['friend_link'])) ? $vars['friend_link'] : "pg/$context/friends/$username/",
			'selected' => ($filter_context == 'friends'),
		),
	);

	echo elgg_view('navigation/tabs', array('tabs' => $tabs));
}
