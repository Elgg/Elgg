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

$chars_left = elgg_echo('thewire:charleft');

$count_down = ($char_limit === 0) ? '' : elgg_format_element('span', [], $char_limit) . " {$chars_left}";
$num_lines = ($char_limit === 0) ? 3 : 2;
	
if ($char_limit > 140) {
	$num_lines = 3;
}

if ($char_limit && !elgg_is_active_plugin('ckeditor')) {
	elgg_require_js('forms/thewire/add');
}

echo elgg_view('input/longtext', [
	'name' => 'body',
	'class' => 'thewire-textarea',
	'rows' => $num_lines,
	'data-max-length' => $char_limit,
	'required' => true,
	'placeholder' => elgg_echo('thewire:form:body:placeholder'),
	'editor_type' => 'thewire',
]);

// form footer
$fields = [
	[
		'#type' => 'submit',
		'text' => $text,
	],
];

if ($char_limit > 0) {
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
