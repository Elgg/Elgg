<?php
/**
 * Featured groups
 *
 * @uses $vars['featured']
 *
 * @package ElggGroups
 */
	 
if ($vars['featured']) {

	elgg_push_context('widgets');
	$body = '';
	foreach ($vars['featured'] as $group) {
		$body .= elgg_view_entity($group, false);
	}
	elgg_pop_context();

	echo elgg_view('layout/objects/module', array(
		'title' => elgg_echo("groups:featured"),
		'body' => $body,
		'class' => 'elgg-aside-module',
	));
}
