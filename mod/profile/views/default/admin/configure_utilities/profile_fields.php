<?php
/**
 * Admin area: edit default profile fields
 */

elgg_import_esm('admin/configure_utilities/profile_fields');

echo elgg_view('output/longtext', [
	'value' => elgg_echo('profile:explainchangefields'),
]);

echo elgg_view('output/url', [
	'icon' => 'plus',
	'text' => elgg_echo('add'),
	'href' => elgg_generate_url('ajax', [
		'type' => 'form',
		'segments' => 'profile/fields/add',
	]),
	'class' => 'elgg-button elgg-button-action elgg-lightbox',
]);

echo elgg_view('admin/configure_utilities/profile_fields/list');

echo elgg_view('output/url', [
	'icon' => 'sync-alt',
	'text' => elgg_echo('reset'),
	'href' => elgg_generate_action_url('profile/fields/reset'),
	'title' => elgg_echo('profile:resetdefault'),
	'confirm' => elgg_echo('profile:resetdefault:confirm'),
	'class' => 'elgg-button elgg-button-cancel',
]);
