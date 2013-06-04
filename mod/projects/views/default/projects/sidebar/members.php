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

$all_link = elgg_view('output/url', array(
	'href' => 'projects/members/' . $vars['entity']->alias,
	'text' => elgg_echo('projects:members:more'),
	'is_trusted' => true,
));

$body = elgg_list_entities_from_relationship(array(
	'relationship' => 'member',
	'relationship_guid' => $vars['entity']->guid,
	'inverse_relationship' => true,
	'type' => 'user',
	'limit' => 0,
	'pagination' => false
));

echo elgg_view_module('aside', elgg_echo('projects:members'), $body);
