<?php

elgg_require_js('forms/site_notifications/process');

echo elgg_format_element('div', ['class' => 'site-notifications-container'], elgg_extract('list', $vars));

// footer
$fields = [
	[
		'#type' => 'button',
		'value' => elgg_echo('site_notifications:toggle_all'),
		'class' => 'elgg-button elgg-button-cancel',
		'id' => 'site-notifications-toggle',
	],
	[
		'#type' => 'submit',
		'value' => elgg_echo('delete'),
		'name' => 'delete',
		'class' => 'elgg-button-delete',
		'data-confirm' => elgg_echo('deleteconfirm:plural'),
	],
];
if ((bool) elgg_extract('mark_read', $vars, true)) {
	$fields[] = [
		'#type' => 'submit',
		'value' => elgg_echo('site_notifications:mark_read'),
		'name' => 'mark_read',
		'data-confirm' => elgg_echo('site_notifications:mark_read:confirm'),
		'formaction' => elgg_generate_action_url('site_notifications/mark_read', [], false),
	];
}

$footer = elgg_view_field([
	'#type' => 'fieldset',
	'fields' => $fields,
	'align' => 'horizontal',
	'class' => 'elgg-level',
]);

elgg_set_form_footer($footer);
