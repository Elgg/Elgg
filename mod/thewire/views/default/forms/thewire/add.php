<?php
/**
 * Wire add form body
 *
 * @uses $vars['post']
 */

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

$count_down = ($char_limit === 0) ? '' : "<span>$char_limit</span> $chars_left";
$num_lines = ($char_limit === 0) ? 3 : 2;
	
if ($char_limit > 140) {
	$num_lines = 3;
}

if ($char_limit) {
	elgg_require_js('forms/thewire/add');
}

echo elgg_view('input/plaintext', [
	'name' => 'body',
	'class' => 'mtm',
	'id' => 'thewire-textarea',
	'rows' => $num_lines,
	'data-max-length' => $char_limit,
	'required' => true,
	'placeholder' => elgg_echo('thewire:form:body:placeholder'),
]);
echo elgg_format_element('div', ['id' => 'thewire-characters-remaining'], $count_down);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => $text,
	'id' => 'thewire-submit-button',
]);
elgg_set_form_footer($footer);
