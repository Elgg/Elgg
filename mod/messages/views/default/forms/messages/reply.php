<?php
/**
 * Reply form
 *
 * @uses $vars['message']
 */

$message = elgg_extract('message', $vars);
if (!$message instanceof ElggMessage) {
	return;
}

// fix for RE: RE: RE: that builds on replies
$reply_title = $message->getDisplayName();
if (strncmp($reply_title, "RE:", 3) != 0) {
	$reply_title = "RE: " . $reply_title;
}

$fields = [
	[
		'#type' => 'hidden',
		'name' => 'recipients[]',
		'value' => $message->fromId,
	],
	[
		'#type' => 'hidden',
		'name' => 'original_guid',
		'value' => $message->guid,
	],
	[
		'#type' => 'text',
		'#label' => elgg_echo('messages:title'),
		'name' => 'subject',
		'value' => $reply_title,
		'required' => true,
	],
	[
		'#type' => 'longtext',
		'#label' => elgg_echo('messages:message'),
		'name' => 'body',
		'required' => true,
		'editor_type' => 'simple',
	],
	
];
foreach ($fields as $field) {
	echo elgg_view_field($field);
}

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('send'),
]);

elgg_set_form_footer($footer);
