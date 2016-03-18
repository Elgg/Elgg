<?php
/**
 * Admin area: edit default profile fields
 */

$add = elgg_view_form('profile/fields/add', ['class' => 'elgg-form-settings'], []);
$list = elgg_view('admin/appearance/profile_fields/list');

$reset = elgg_view('output/url', [
	'text' => elgg_echo('reset'),
	'href' => 'action/profile/fields/reset',
	'title' => elgg_echo('profile:resetdefault'),
	'confirm' => elgg_echo('profile:resetdefault:confirm'),
	'class' => 'elgg-button elgg-button-cancel',
	'is_trusted' => 'true',
]);

echo $add . $list;
echo elgg_format_element('div', ['class' => 'mtl'], $reset);
