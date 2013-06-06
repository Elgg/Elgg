<?php
/**
 * Projects members sidebar
 *
 * @package Coopfunding
 * @subpackage Projects
 *
 * @uses $vars['entity'] Project entity
 * @uses $vars['limit']  The number of members to display
 */


if (elgg_is_logged_in() && elgg_is_active_plugin('projects-contact')) {
	$contact_link = elgg_view('output/url', array(
		'href' => "projects_contact/add/{$params['entity']->alias}",
		'text' => elgg_echo('projects_contact:add'),
		'is_trusted' => true,
	));
}

$body = elgg_list_entities_from_relationship(array(
	'relationship' => 'member',
	'relationship_guid' => $vars['entity']->guid,
	'inverse_relationship' => true,
	'type' => 'user',
	'limit' => 0,
	'pagination' => false
));

$body .= "<div class='center mts'>$contact_link</div>";

echo elgg_view_module('aside', elgg_echo('projects:members'), $body);
