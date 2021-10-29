<?php
/**
 * Edit profile display name
 *
 * @uses vars['entity']
 */

/* @var ElggUser $entity */
$entity = elgg_extract('entity', $vars);

echo elgg_view_field([
	'#type' => 'text',
	'name' => 'name',
	'value' => $entity->name,
	'#label' => elgg_echo('user:name:label'),
	'maxlength' => 50, // hard coded in /actions/profile/edit
]);
