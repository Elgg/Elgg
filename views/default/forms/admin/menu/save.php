<?php
/**
 * Form body for setting up site menu
 */

$num_featured_items = elgg_extract('num_featured_items', $vars, 6);

// get site menu items
$menu = elgg()->menus->getUnpreparedMenu('site', [
	'sort_by' => 'name',
]);

$menu_items = $menu->getItems();

$featured_menu_names = elgg_get_config('site_featured_menu_names');

$dropdown_values = [];
foreach ($menu_items as $item) {
	$dropdown_values[$item->getName()] = $item->getText();
}
$dropdown_values[' '] = elgg_echo('none');

$configure = elgg_view('output/longtext', ['value' => elgg_echo('admin:menu_items:description')]);

for ($i=0; $i < $num_featured_items; $i++) {
	if ($featured_menu_names && array_key_exists($i, $featured_menu_names)) {
		$current_value = $featured_menu_names[$i];
	} else {
		$current_value = ' ';
	}

	$configure .= elgg_view('input/select', [
		'options_values' => $dropdown_values,
		'name' => 'featured_menu_names[]',
		'value' => $current_value,
	]);
}

echo elgg_view_module('inline', elgg_echo('admin:menu_items:configure'), $configure);

$custom_items = elgg_get_config('site_custom_menu_items');

$name_str = elgg_echo('name');
$url_str = elgg_echo('admin:plugins:label:website');

$add_menu_items = '';
if (is_array($custom_items)) {
	foreach ($custom_items as $title => $url) {
		$name_input = elgg_view('input/text', [
			'name' => 'custom_menu_titles[]',
			'value' => $title,
		]);

		$url_input = elgg_view('input/text', [
			'name' => 'custom_menu_urls[]',
			'value' => $url,
		]);

		$add_menu_items .= elgg_format_element('li', [], "$name_str: $name_input $url_str: $url_input $delete");
	}
}

$new = elgg_echo('new');
$name_input = elgg_view('input/text', ['name' => 'custom_menu_titles[]']);

$url_input = elgg_view('input/text', ['name' => 'custom_menu_urls[]']);

$add_menu_items .= elgg_format_element([
	'#tag_name' => 'li',
	'class' => 'custom_menuitem',
	'#text' => "$name_str: $name_input $url_str: $url_input",
]);

$add_menu = elgg_view('output/longtext', ['value' => elgg_echo('admin:add_menu_item:description')]);
$add_menu .= elgg_format_element('ul', ['class' => 'elgg-list elgg-list-simple'], $add_menu_items);

echo elgg_view_module('inline', elgg_echo('admin:add_menu_item'), $add_menu);

echo elgg_view('input/submit', ['value' => elgg_echo('save')]);
