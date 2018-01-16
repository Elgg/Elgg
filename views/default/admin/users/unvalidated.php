<?php
/**
 * List all unvalidated users in the admin area
 *
 * Allows for manual actions like validate/delete
 */


$form = elgg_view_form('admin/users/unvalidated', [
	'id' => 'admin-users-unvalidated-bulk',
]);

if (empty($form)) {
	echo elgg_view('output/longtext', [
		'value' => elgg_echo('admin:users:unvalidated:no_results'),
	]);
	return;
}

elgg_load_css('admin/users/unvalidated');

// add header
$header = elgg_view_menu('user:unvalidated:bulk', [
	'class' => 'elgg-menu-hz',
]);

// show list
echo elgg_view_module('info', '', $form, ['header' => $header]);
