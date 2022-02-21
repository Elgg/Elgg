<?php
/**
 * Show a listing of all users on the community
 */

echo elgg_view('admin/users/header', [
	'filter' => 'all',
]);

$buttons = [
	[
		'#type' => 'submit',
		'icon' => 'ban',
		'value' => elgg_echo('ban'),
		'formaction' => elgg_generate_action_url('admin/user/bulk/ban', [], false),
	],
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
	'filter' => 'all',
]);
