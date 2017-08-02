<?php
/**
 * Admin area: edit default profile fields
 */

elgg_require_js('admin/configure_utilities/profile_fields');

echo elgg_view('output/longtext', [
	'value' => elgg_echo('profile:explainchangefields'),
]);

echo elgg_view('output/url', [
	'text' => elgg_echo('add'),
	'href' => 'ajax/form/profile/fields/add',
	'class' => 'elgg-button elgg-button-action elgg-lightbox',
]);

echo elgg_view('admin/configure_utilities/profile_fields/list');

echo elgg_view('output/url', [
	'text' => elgg_echo('reset'),
	'href' => 'action/profile/fields/reset',
	'title' => elgg_echo('profile:resetdefault'),
	'confirm' => elgg_echo('profile:resetdefault:confirm'),
	'class' => 'elgg-button elgg-button-cancel',
	'is_trusted' => 'true',
]);
