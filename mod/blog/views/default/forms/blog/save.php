<?php
/**
 * Edit blog form
 */

elgg_import_esm('forms/blog/save');

$blog = elgg_extract('entity', $vars);

echo elgg_view('entity/edit/header', [
	'entity' => $blog,
	'entity_type' => 'object',
	'entity_subtype' => 'blog',
]);

$fields = elgg()->fields->get('object', 'blog');
foreach ($fields as $field) {
	$name = elgg_extract('name', $field);
	
	switch (elgg_extract('#type', $field)) {
		case 'checkbox':
			$value = elgg_extract('value', $field);
			$field['checked'] = isset($value) ? $value === elgg_extract($name, $vars) : null;
			break;
		case 'access':
			if ($blog instanceof \ElggBlog) {
				$field['entity'] = $blog;
			}
			
			// fall through to set value
		default:
			$field['value'] = elgg_extract($name, $vars);
			break;
	}
	
	echo elgg_view_field($field);
}

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $blog?->guid,
]);

echo elgg_view_field([
	'#type' => 'container_guid',
	'value' => elgg_extract('container_guid', $vars),
	'entity_type' => 'object',
	'entity_subtype' => 'blog',
]);

$saved = $blog instanceof \ElggBlog ? elgg_view('output/friendlytime', ['time' => $blog->time_updated]) : elgg_echo('never');
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
if (!$blog instanceof \ElggBlog || $blog->status != 'published') {
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

elgg_set_form_footer($footer);
