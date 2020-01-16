<?php
/**
 * Edit blog form
 */

elgg_require_js('elgg/blog/save_draft');

$blog = get_entity($vars['guid']);
$vars['entity'] = $blog;

$draft_warning = elgg_extract('draft_warning', $vars);
if ($draft_warning) {
	echo '<span class="mbm elgg-text-help">' . $draft_warning . '</span>';
}

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
		'value' => elgg_html_decode(elgg_extract('excerpt', $vars)),
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
		'#type' => 'select',
		'name' => 'comments_on',
		'id' => 'blog_comments_on',
		'value' => elgg_extract('comments_on', $vars),
		'options_values' => [
			'On' => elgg_echo('on'),
			'Off' => elgg_echo('off'),
		],
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
		'#type' => 'hidden',
		'name' => 'container_guid',
		'value' => elgg_get_page_owner_guid(),
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

$save_status = elgg_echo('blog:save_status');
if ($blog) {
	$saved = date('F j, Y @ H:i', $blog->time_updated);
} else {
	$saved = elgg_echo('never');
}

$footer = <<<___HTML
<div class="elgg-subtext mbm">
	$save_status <span class="blog-save-status-time">$saved</span>
</div>
___HTML;

$footer .= elgg_view('input/submit', [
	'value' => elgg_echo('save'),
	'name' => 'save',
]);

// published blogs do not get the preview button
if (!$blog || $blog->status != 'published') {
	$footer .= elgg_view('input/submit', [
		'value' => elgg_echo('preview'),
		'name' => 'preview',
		'class' => 'elgg-button-submit mls',
	]);
}

if ($blog) {
	// add a delete button if editing
	$footer .= elgg_view('output/url', [
		'href' => elgg_generate_action_url('entity/delete', [
			'guid' => $blog->guid,
		]),
		'text' => elgg_echo('delete'),
		'class' => 'elgg-button elgg-button-delete float-alt',
		'confirm' => true,
	]);
}

elgg_set_form_footer($footer);
