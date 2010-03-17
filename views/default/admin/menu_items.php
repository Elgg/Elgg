<?php
/**
 * Elgg administration menu items
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

$menu_items = $vars['menu_items'];
$featured_urls = get_config('menu_items_featured_urls');

// get an alphabetical sort of the items + urls
foreach ($menu_items as $name => $info) {
	$menu_sorted[$info->name] = $info->value->url;
}

ksort($menu_sorted);

$pulldown_values = array_flip($menu_sorted);
$pulldown_values[''] = elgg_echo('none');

echo elgg_view_title(elgg_echo('admin:menu_items'));
echo elgg_view('output/longtext', array('value' => elgg_echo("admin:menu_items:description")));

$form_body = '';

// @todo Could probably make this number configurable
for ($i=0; $i<7; $i++) {
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
$form_body .= '<br /><br />';
$form_body .= '<label for="menu_items_hide_toolbar_entries">'
	. elgg_echo('admin:menu_items:hide_toolbar_entries') . '</label>';
$form_body .= elgg_view('input/pulldown', array(
	'internalname' => 'menu_items_hide_toolbar_entries',
	'internalid' => 'menu_items_hide_toolbar_entries',
	'value' => get_config('menu_items_hide_toolbar_entries'),
	'options_values' => array(
		'yes' => elgg_echo('option:yes'),
		'no' => elgg_echo('option:no')
)));

$form_body .= '<br /><br />';
$form_body .= elgg_view('input/submit', array('value' => elgg_echo('save')));

echo elgg_view('input/form', array(
	'body' => $form_body,
	'action' => "{$vars['url']}action/admin/menu_items"
));