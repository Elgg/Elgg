<?php
/**
 * Show a listing of all banned users
 */

echo elgg_view('admin/users/header', [
	'filter' => 'banned',
]);

$buttons = [
	[
		'#type' => 'submit',
		'icon' => 'ban',
		'value' => elgg_echo('unban'),
		'formaction' => elgg_generate_action_url('admin/user/bulk/unban', [], false),
	],
	[
		'#type' => 'submit',
		'icon' => 'delete',
		'class' => 'elgg-button-delete',
		'value' => elgg_echo('delete'),
		'formaction' => elgg_generate_action_url('admin/user/bulk/delete', [], false),
		'confirm' => elgg_echo('deleteconfirm:plural'),
	],
];

echo elgg_view_form('admin/users/bulk_actions', [
	'prevent_double_submit' => false,
], [
	'buttons' => $buttons,
	'filter' => 'banned',
	'options' => [
		'metadata_name_value_pairs' => [
			'banned' => 'yes',
		],
	],
]);
