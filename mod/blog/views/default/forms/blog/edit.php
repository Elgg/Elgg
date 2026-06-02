<?php
/**
 * Edit blog form
 */

elgg_import_esm('forms/blog/edit');

$entity = elgg_extract('entity', $vars);

$saved = $entity instanceof \ElggBlog ? elgg_view('output/friendlytime', ['time' => $entity->time_updated]) : elgg_echo('never');
$saved = elgg_format_element('span', ['class' => 'blog-save-status-time'], $saved);

$footer = elgg_format_element('div', ['class' => ['elgg-subtext', 'mbm']], elgg_echo('blog:save_status') . ' ' . $saved);

$buttons = [];
$buttons[] = [
	'#type' => 'submit',
	'name' => 'save',
	'value' => 1,
	'text' => elgg_echo('save'),
];

// published blogs do not get the preview button
if (!$entity instanceof \ElggBlog || $entity->status != 'published') {
	$buttons[] = [
		'#type' => 'button',
		'name' => 'preview',
		'value' => 1,
		'text' => elgg_echo('preview'),
		'class' => 'elgg-button-action',
	];
}

$footer .= elgg_view_field([
	'#type' => 'fieldset',
	'align' => 'horizontal',
	'fields' => $buttons,
]);

$vars['footer'] = $footer;
$vars['add_header_image'] = true;

echo elgg_view('forms/entity/edit', $vars);
