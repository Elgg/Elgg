<?php
/**
 * Profile fields list
 */

$fieldlist = elgg_get_config('profile_custom_fields');
if (empty($fieldlist) && $fieldlist !== '0') {
	return;
}

$item_list = '';
$fieldlistarray = explode(',', $fieldlist);
foreach ($fieldlistarray as $name) {
	$title = elgg_get_config("admin_defined_profile_{$name}");
	$type = elgg_get_config("admin_defined_profile_type_{$name}");
	if (!$title || !$type) {
		continue;
	}
	
	$url = elgg_view('output/url', [
		'icon' => 'edit',
		'text' => false,
		'href' => "ajax/form/profile/fields/add?id={$name}",
		'class' => 'elgg-lightbox',
	]);
	$url .= ' ' . elgg_view('output/url', [
		'icon' => 'delete-alt',
		'text' => false,
		'href' => "action/profile/fields/delete?id={$name}",
		'confirm' => elgg_echo('deleteconfirm'),
	]);
	$type = elgg_echo("profile:field:{$type}");
	$drag_arrow = elgg_view_icon('arrows-alt', ['class' => 'elgg-state-draggable']);
	
	$text = "<b>{$title}</b> [{$type}] {$url}";
	$item_list .= elgg_format_element('li', [
		'id' => $name,
	], $drag_arrow . ' ' . $text);
}

echo elgg_format_element('ul', [
	'id' => 'elgg-profile-fields',
	'class' => 'mvm',
], $item_list);
