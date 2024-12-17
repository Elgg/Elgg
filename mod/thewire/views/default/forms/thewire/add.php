<?php
/**
 * Wire add form body
 *
 * @uses $vars['post']
 */

elgg_require_css('forms/thewire/add');

$post = elgg_extract('post', $vars);
$char_limit = (int) elgg_get_plugin_setting('limit', 'thewire');

$text = elgg_echo('post');
if ($post instanceof \ElggWire) {
	$text = elgg_echo('reply');
	
	echo elgg_view_field([
		'#type' => 'hidden',
		'name' => 'parent_guid',
		'value' => $post->guid,
	]);
}

if ($char_limit && !elgg_is_active_plugin('ckeditor')) {
	elgg_import_esm('forms/thewire/add');
}

$fields = elgg()->fields->get('object', 'thewire');

foreach ($fields as $field) {
	$name = $field['name'];
	
	$field['value'] = elgg_extract($name, $vars);
	
	echo elgg_view_field($field);
}

// form footer
$fields = [
	[
		'#type' => 'submit',
		'text' => $text,
	],
];

if ($char_limit > 0) {
	$count_down = elgg_format_element('span', [], $char_limit) . ' ' . elgg_echo('thewire:charleft');
	
	$chars = elgg_format_element('div', ['class' => 'elgg-field-input'], $count_down);
	
	$fields[] = [
		'#html' => elgg_format_element('div', ['class' => ['elgg-field', 'thewire-characters-wrapper']], $chars),
	];
}

$footer = elgg_view_field([
	'#type' => 'fieldset',
	'align' => 'horizontal',
	'class' => 'elgg-fieldset-wrap',
	'fields' => $fields,
]);

elgg_set_form_footer($footer);
