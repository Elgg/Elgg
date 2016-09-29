<?php
/**
 * Add/edit collection
 *
 * @uses $vars['collection_name']    Name of the collection
 * @uses $vars['collection_friends'] Friendpicker value
 * @uses $vars['collection_id']      ID of the collection
 */

echo elgg_view_field([
	'#type' => 'text',
	'#label' => elgg_echo('friends:collections:name'),
	'name' => 'collection_name',
	'value' => elgg_extract('collection_name', $vars),
	'required' => true,
]);

echo elgg_view_field([
	'#type' => 'friendspicker',
	'#label' => elgg_echo('friends:collections:friends'),
	'#help' => elgg_echo('friends:collections:friends:help'),
	'name' => 'collection_friends',
	'value' => elgg_extract('collection_friends', $vars),
	'required' => true,
]);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'collection_id',
	'value' => elgg_extract('collection_id', $vars),
]);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);
elgg_set_form_footer($footer);

