<?php
/**
 * Edit / add a bookmark
 */

$categories_vars = $vars;
$categories_vars['#type'] = 'categories';

$fields = [
	[
		'#label' => elgg_echo('title'),
		'#type' => 'text',
		'required' => true,
		'name' => 'title',
		'value' => elgg_extract('title', $vars),
	],
	[
		'#label' => elgg_echo('bookmarks:address'),
		'#type' => 'url',
		'required' => true,
		'name' => 'address',
		'value' => elgg_extract('address', $vars),
	],
	[
		'#label' => elgg_echo('description'),
		'#type' => 'longtext',
		'name' => 'description',
		'value' => elgg_extract('description', $vars),
		'editor_type' => 'simple',
	],
	[
		'#label' => elgg_echo('tags'),
		'#type' => 'tags',
		'name' => 'tags',
		'id' => 'blog_tags',
		'value' => elgg_extract('tags', $vars),
	],
	$categories_vars,
	[
		'#label' => elgg_echo('access'),
		'#type' => 'access',
		'name' => 'access_id',
		'value' => elgg_extract('access_id', $vars, ACCESS_DEFAULT),
		'entity' => get_entity(elgg_extract('guid', $vars)),
		'entity_type' => 'object',
		'entity_subtype' => 'bookmarks',
	],
	[
		'#type' => 'hidden',
		'name' => 'container_guid',
		'value' => elgg_extract('container_guid', $vars),
	],
	[
		'#type' => 'hidden',
		'name' => 'guid',
		'value' => elgg_extract('guid', $vars),
	],
];

foreach ($fields as $field) {
	echo elgg_view_field($field);
}

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);
elgg_set_form_footer($footer);
