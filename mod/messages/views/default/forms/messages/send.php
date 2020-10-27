<?php
/**
 * Compose message form
 *
 * @uses $vars['recipients']
 * @uses $vars['subject']
 * @uses $vars['body']
 */

$recipients = elgg_extract('recipients', $vars);
$subject = elgg_extract('subject', $vars, '');
$body = elgg_extract('body', $vars, '');

$fields = [
	[
		'#type' => 'userpicker',
		'#label' => elgg_echo('email:to'),
		'#help' => elgg_echo('messages:to:help'),
		'name' => 'recipients',
		'values' => $recipients,
		'limit' => 1,
		'required' => true,
		'only_friends' => (bool) elgg_get_plugin_setting('friends_only', 'messages'),
	],
	[
		'#type' => 'text',
		'#label' => elgg_echo('messages:title'),
		'name' => 'subject',
		'value' => $subject,
		'required' => true,
	],
	[
		'#type' => 'longtext',
		'#label' => elgg_echo('messages:message'),
		'name' => 'body',
		'value' => $body,
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
