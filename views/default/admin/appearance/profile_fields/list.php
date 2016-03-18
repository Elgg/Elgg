<?php
/**
 * Profile fields.
 *
 * @todo Needs some review
 */

$items = [];
$fieldlist = elgg_get_config('profile_custom_fields');

if ($fieldlist || $fieldlist === '0') {
	$fieldlistarray = explode(',', $fieldlist);
	foreach ($fieldlistarray as $listitem) {
		$translation = elgg_get_config("admin_defined_profile_$listitem");
		$type = elgg_get_config("admin_defined_profile_type_$listitem");
		if ($translation && $type) {
			$item = new stdClass;
			$item->translation = $translation;
			$item->shortname = $listitem;
			$item->name = "admin_defined_profile_$listitem";
			$item->type = elgg_echo("profile:field:$type");
			$items[] = $item;
		}
	}
}

$list_items = '';
foreach ($items as $item) {
	$url = elgg_view('output/url', [
		'href' => "action/profile/fields/delete?id={$item->shortname}",
		'text' => elgg_view_icon('delete-alt'),
		'is_action' => true,
		'is_trusted' => true,
	]);
	$type = elgg_echo($item->type);
	
	$title = elgg_format_element('span', [
		'id' => "elgg-profile-field-{$item->shortname}",
		'class' => 'elgg-state-editable',
	], $item->translation);
	
	$field = elgg_view_icon('drag-arrow', 'elgg-state-draggable');
	$field .= elgg_format_element('b', [], $title);
	$field .= " [$type] $url";
	
	$list_items .= elgg_format_element('li', ['id' => $item->shortname, 'class' => 'clearfix'], $field);
}

echo elgg_format_element('ul', ['id' => 'elgg-profile-fields', 'class' => 'mvm'], $list_items);
