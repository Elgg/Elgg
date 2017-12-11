<?php
/**
 * Form body for setting up site menu
 */

// get site menu items
$menu = elgg()->menus->getUnpreparedMenu('site', [
	'sort_by' => 'name',
]);

$menu_items = $menu->getItems();

$num_featured_items = elgg_extract('num_featured_items', $vars, count($menu_items));

$featured_menu_names = elgg_get_config('site_featured_menu_names');

$dropdown_values = [];
foreach ($menu_items as $item) {
	$dropdown_values[$item->getName()] = $item->getText();
}
$dropdown_values[' '] = elgg_echo('none');

$configure = elgg_view('output/longtext', [
	'value' => elgg_echo('admin:menu_items:description'),
	'class' => 'elgg-text-help',
]);

$fields = [];
for ($i = 0; $i < $num_featured_items; $i++) {
	if ($featured_menu_names && array_key_exists($i, $featured_menu_names)) {
		$current_value = $featured_menu_names[$i];
	} else {
		$current_value = ' ';
	}

	$fields[] = [
		'#type' => 'select',
		'#class' => 'is-3',
		'options_values' => $dropdown_values,
		'name' => 'featured_menu_names[]',
		'value' => $current_value,
	];
}

$configure .= elgg_view_field([
	'#type' => 'fieldset',
	'align' => 'horizontal',
	'fields' => $fields,
]);

echo elgg_view_module('info', elgg_echo('admin:menu_items:configure'), $configure);

$add_menu = elgg_view('output/longtext', [
	'value' => elgg_echo('admin:add_menu_item:description'),
	'class' => 'elgg-text-help',
]);

$custom_items = (array) elgg_get_config('site_custom_menu_items');
$custom_items[''] = ''; // add empty option

$name_str = elgg_echo('name');
$url_str = elgg_echo('admin:plugins:label:website');

if (is_array($custom_items)) {
	foreach ($custom_items as $title => $url) {
		$add_menu .= elgg_view_field([
			'#type' => 'fieldset',
			'align' => 'horizontal',
			'fields' => [
				[
					'#type' => 'text',
					'#label' => $name_str,
					'name' => 'custom_menu_titles[]',
					'value' => $title,
				],
				[
					'#type' => 'text',
					'#label' => $url_str,
					'name' => 'custom_menu_urls[]',
					'value' => $url,
				]
			],
		]);
	}
}

echo elgg_view_module('info', elgg_echo('admin:add_menu_item'), $add_menu);

$footer = elgg_view_field([
	'#type' => 'submit',
	'value' => elgg_echo('save'),
]);

elgg_set_form_footer($footer);

