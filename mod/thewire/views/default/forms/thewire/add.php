<?php
/**
 * Wire add form body
 *
 * @uses $vars['post']
 */

elgg_require_js('elgg/thewire');

$guid = (int) elgg_extract('guid', $vars);
$entity = get_entity($guid);

$post = elgg_extract('post', $vars);
$char_limit = (int) elgg_get_plugin_setting('limit', 'thewire');

$text = elgg_echo('post');
if ($post) {
	$text = elgg_echo('reply');
}
$chars_left = elgg_echo('thewire:charleft');

$guid_input = '';
if ($guid) {
	$guid_input = elgg_view('input/hidden', [
		'name' => 'guid',
		'value' => $guid,
	]);
}

$parent_guid = 0;

if ($guid) {
	$parent_guid = (int) $entity->wire_thread;
}

if ($post) {
	$parent_guid = (int) $post->guid;
}

$count_down = "<span>$char_limit</span> $chars_left";
$num_lines = 2;
if ($char_limit == 0) {
	$num_lines = 3;
	$count_down = '';
} else if ($char_limit > 140) {
	$num_lines = 3;
}

echo elgg_view_field([
	'#type' => 'plaintext',
	'name' => 'body',
	'class' => 'mtm',
	'id' => 'thewire-textarea',
	'rows' => $num_lines,
	'data-max-length' => $char_limit,
	'required' => true,
	'placeholder' => $guid ? false : elgg_echo('thewire:form:body:placeholder'),
	'value' => $guid ? $entity->description : '',
]);

echo elgg_format_element('div', ['id' => 'thewire-characters-remaining'], $count_down);

$fields = [
	[
		'#type' => 'hidden',
		'name' => 'parent_guid',
		'value' => $parent_guid,
	],
	[
		'#type' => 'hidden',
		'name' => 'guid',
		'value' => $guid,
	],
];

foreach ($fields as $field) {
	echo elgg_view_field($field);
}

$footer = elgg_view('input/submit', [
	'value' => $text,
	'id' => 'thewire-submit-button',
]);

elgg_set_form_footer($footer);
