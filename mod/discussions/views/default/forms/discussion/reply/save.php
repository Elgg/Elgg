<?php

/**
 * Discussion topic reply form body
 *
 * @uses $vars['topic']  A discussion topic object
 * @uses $vars['entity'] A discussion reply object
 * @uses $vars['inline'] Display a shortened form?
 */
$topic = elgg_extract('topic', $vars);
$reply = elgg_extract('entity', $vars);
$inline = elgg_extract('inline', $vars, false);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'topic_guid',
	'value' => $topic ? $topic->guid : '',
]);

echo elgg_view_field([
	'#type' => 'hidden',
	'name' => 'guid',
	'value' => $reply ? $reply->guid : '',
]);

if ($reply) {
	$label = elgg_echo('discussion:reply:edit');
	$value = $reply->description;
	$action = elgg_echo('save');
} else {
	$label = elgg_echo('reply:this');
	$value = '';
	$action = elgg_echo('reply');
}

echo elgg_view_field([
	'#type' => 'longtext',
	'#label' => $label,
	'required' => true,
	'name' => 'description',
	'value' => $value,
	'visual' => !$inline,
	'editor_type' => 'simple',
]);

$buttons = [
	[
		'#type' => 'submit',
		'value' => $action,
	]
];

if ($inline) {
	$buttons[] = [
		'#type' => 'button',
		'type' => 'reset',
		'text' => elgg_echo('cancel'),
		'class' => 'elgg-button-cancel',
	];
}

$footer = elgg_view_field([
	'#type' => 'fieldset',
	'fields' => $buttons,
	'align' => 'horizontal',
]);

elgg_set_form_footer($footer);
