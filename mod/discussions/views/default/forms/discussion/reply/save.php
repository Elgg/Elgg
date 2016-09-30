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

echo elgg_view_input('hidden', array(
	'name' => 'topic_guid',
	'value' => $topic ? $topic->guid : '',
));

echo elgg_view_input('hidden', array(
	'name' => 'guid',
	'value' => $reply ? $reply->guid : '',
));

echo elgg_view_input('longtext', array(
	'name' => 'description',
	'value' => $reply ? $reply->description : '',
	'visual' => !$inline,
	'label' => $reply ? elgg_echo("discussion:reply:edit") : elgg_echo("reply:this"),
));

$buttons = [
	[
		'#type' => 'submit',
		'value' => $reply ? elgg_echo('save') : elgg_echo('reply'),
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

$footer = elgg_view_input('fieldset', [
	'fields' => $buttons,
	'align' => 'horizontal',
]);

elgg_set_form_footer($footer);
