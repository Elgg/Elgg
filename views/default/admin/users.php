<?php
/**
 * Show a listing of all users on the community
 */

echo elgg_view('admin/users/header', [
	'filter' => 'all',
]);

echo elgg_view_form('admin/users/bulk_actions', [
	'prevent_double_submit' => false,
], [
	'filter' => 'all',
]);
