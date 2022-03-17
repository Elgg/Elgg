<?php
/**
 * Show a listing of all site administrators
 */

echo elgg_view('admin/users/header', [
	'filter' => 'admin',
]);

echo elgg_view_form('admin/users/bulk_actions', [
	'prevent_double_submit' => false,
], [
	'filter' => 'admins',
	'options' => [
		'metadata_name_value_pairs' => [
			'admin' => 'yes',
		],
	],
]);
