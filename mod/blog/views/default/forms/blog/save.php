<?php
/**
 * Edit blog form
 */

elgg_require_js('forms/blog/save');

$blog = elgg_extract('entity', $vars);

echo elgg_view('entity/edit/header', [
	'entity' => $blog,
	'entity_type' => 'object',
	'entity_subtype' => 'blog',
]);

$categories_vars = $vars;
$categories_vars['#type'] = 'categories';

$fields = [
	[
		'#label' => elgg_echo('title'),
		'#type' => 'text',
		'name' => 'title',
		'required' => true,
		'id' => 'blog_title',
		'value' => elgg_extract('title', $vars),
	],
	[
		'#label' => elgg_echo('blog:excerpt'),
		'#type' => 'text',
		'name' => 'excerpt',
		'id' => 'blog_excerpt',
		'value' => elgg_extract('excerpt', $vars),
	],
	[
		'#label' => elgg_echo('blog:body'),
		'#type' => 'longtext',
		'name' => 'description',
		'required' => true,
		'id' => 'blog_description',
		'value' => elgg_extract('description', $vars),
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
		'#label' => elgg_echo('comments'),
		'#type' => 'checkbox',
		'name' => 'comments_on',
		'id' => 'blog_comments_on',
		'default' => 'Off',
		'value' => 'On',
		'switch' => true,
		'checked' => elgg_extract('comments_on', $vars) === 'On',
	],
	[
		'#label' => elgg_echo('access'),
		'#type' => 'access',
		'name' => 'access_id',
		'id' => 'blog_access_id',
		'value' => elgg_extract('access_id', $vars),
		'entity' => elgg_extract('entity', $vars),
		'entity_type' => 'object',
		'entity_subtype' => 'blog',
	],
	[
		'#label' => elgg_echo('status'),
		'#type' => 'select',
		'name' => 'status',
		'id' => 'blog_status',
		'value' => elgg_extract('status', $vars),
		'options_values' => [
			'draft' => elgg_echo('status:draft'),
			'published' => elgg_echo('status:published'),
		],
	],
	[
		'#type' => 'container_guid',
		'entity_type' => 'object',
		'entity_subtype' => 'blog',
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

$saved = $blog instanceof \ElggBlog ? elgg_view('output/friendlytime', ['time' => $blog->time_updated]) : elgg_echo('never');
$saved = elgg_format_element('span', ['class' => 'blog-save-status-time'], $saved);

$footer = elgg_format_element('div', ['class' => ['elgg-subtext', 'mbm']], elgg_echo('blog:save_status') . ' ' . $saved);

$footer .= elgg_view('input/submit', [
	'name' => 'save',
	'value' => 1,
	'text' => elgg_echo('save'),
]);

// published blogs do not get the preview button
if (!$blog instanceof \ElggBlog || $blog->status != 'published') {
	$footer .= elgg_view('input/button', [
		'name' => 'preview',
		'value' => 1,
		'text' => elgg_echo('preview'),
		'class' => 'elgg-button-action mls',
	]);
}

elgg_set_form_footer($footer);
