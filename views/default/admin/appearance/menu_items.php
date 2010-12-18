<?php
/**
 * Elgg administration menu items
 *
 * @package Elgg
 * @subpackage Core
 */

$builder = new ElggMenuBuilder('site');
$menu = $builder->getMenu('name');
$menu_items = $menu['default'];

$featured_menu_names = elgg_get_config('site_featured_menu_names');

$dropdown_values = array();
foreach ($menu_items as $item) {
	$dropdown_values[$item->getName()] = $item->getTitle();
}
$dropdown_values[''] = elgg_echo('none');

echo elgg_view_title(elgg_echo('admin:menu_items'));
echo "<div class='admin_settings menuitems'><h3>".elgg_echo('admin:menu_items:configure')."</h3>";
echo "<p class='margin-top'>".strip_tags(elgg_view('output/longtext', array('value' => elgg_echo("admin:menu_items:description"))))."</p>";
$form_body = '';

// @todo Could probably make this number configurable
for ($i=0; $i<6; $i++) {
	if (array_key_exists($i, $featured_menu_names)) {
		$current_value = $featured_menu_names[$i];
	} else {
		$current_value = '';
	}

	$form_body .= elgg_view('input/pulldown', array(
		'options_values' => $dropdown_values,
		'internalname' => 'featured_menu_names[]',
		'value' => $current_value
	));
}

// add arbitrary links
$form_body .= "<h3>".elgg_echo('admin:add_menu_item')."</h3>";
$form_body .= elgg_view('output/longtext', array('value' => elgg_echo("admin:add_menu_item:description")));

$custom_items = elgg_get_config('site_custom_menu_items');

$name_str = elgg_echo('name');
$url_str = elgg_echo('admin:plugins:label:website');

$form_body .= '<ul class="custom_menuitems">';

if (is_array($custom_items)) {
	foreach ($custom_items as $title => $url) {
		$name_input = elgg_view('input/text', array(
			'internalname' => 'custom_menu_titles[]',
			'value' => $title
		));

		$url_input = elgg_view('input/text', array(
			'internalname' => 'custom_menu_urls[]',
			'value' => $url
		));

		$form_body .= "<li class='custom_menuitem'>$name_str: $name_input $url_str: $url_input $delete</li>";
	}
}

$new = elgg_echo('new');
$name_input = elgg_view('input/text', array(
	'internalname' => 'custom_menu_titles[]',
));

$url_input = elgg_view('input/text', array(
	'internalname' => 'custom_menu_urls[]',
));

$form_body .= "<li class='custom_menuitem'>$name_str: $name_input $url_str: $url_input</li>
</ul>";

$form_body .= elgg_view('input/submit', array('value' => elgg_echo('save')));

echo elgg_view('input/form', array(
	'body' => $form_body,
	'action' => "action/admin/menu/save"
));
echo "</div>";