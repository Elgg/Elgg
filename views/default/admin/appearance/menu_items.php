<?php
/**
 * Elgg administration menu items
 *
 * @package Elgg
 * @subpackage Core
 */

$menu_items = get_register('menu');
$featured_urls = get_config('menu_items_featured_urls');

// get an alphabetical sort of the items + urls
foreach ($menu_items as $name => $info) {
	$menu_sorted[$info->name] = $info->value->url;
}

ksort($menu_sorted);

$pulldown_values = array_flip($menu_sorted);
$pulldown_values[''] = elgg_echo('none');

echo elgg_view_title(elgg_echo('admin:menu_items'));
echo "<div class='admin_settings menuitems'><h3>".elgg_echo('admin:menu_items:configure')."</h3>";
echo "<p class='margin-top'>".strip_tags(elgg_view('output/longtext', array('value' => elgg_echo("admin:menu_items:description"))))."</p>";
$form_body = '';

// @todo Could probably make this number configurable
for ($i=0; $i<6; $i++) {
	if (array_key_exists($i, $featured_urls)) {
		$current_value = $featured_urls[$i]->value->url;
	} else {
		$current_value = '';
	}

	$form_body .= elgg_view('input/pulldown', array(
		'options_values' => $pulldown_values,
		'internalname' => 'featured_urls[]',
		'value' => $current_value
	));
}

// add arbitrary links
$form_body .= "<h3>".elgg_echo('admin:add_menu_item')."</h3>";
$form_body .= elgg_view('output/longtext', array('value' => elgg_echo("admin:add_menu_item:description")));

$custom_items = get_config('menu_items_custom_items');

$name_str = elgg_echo('name');
$url_str = elgg_echo('admin:plugins:label:website');

$form_body .= '<ul class="custom_menuitems">';

if (is_array($custom_items)) {
	foreach ($custom_items as $url => $name) {
		$name_input = elgg_view('input/text', array(
			'internalname' => 'custom_item_names[]',
			'value' => $name
		));

		$url_input = elgg_view('input/text', array(
			'internalname' => 'custom_item_urls[]',
			'value' => $url
		));

		$form_body .= "<li class='custom_menuitem'>$name_str: $name_input $url_str: $url_input $delete</li>";
	}
}

$new = elgg_echo('new');
$name_input = elgg_view('input/text', array(
	'internalname' => 'custom_item_names[]',
));

$url_input = elgg_view('input/text', array(
	'internalname' => 'custom_item_urls[]',
));

$form_body .= "<li class='custom_menuitem'>$name_str: $name_input $url_str: $url_input</li>
</ul>";

$form_body .= elgg_view('input/submit', array('value' => elgg_echo('save')));

echo elgg_view('input/form', array(
	'body' => $form_body,
	'action' => "action/admin/menu_items"
));
echo "</div>";