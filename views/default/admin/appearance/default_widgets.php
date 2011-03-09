<?php
/**
 * Default widgets landing page.
 *
 * @package Elgg.Core
 * @subpackage Administration.DefaultWidgets
 */

elgg_push_context('default_widgets');
$widget_context = get_input('widget_context');
$list = elgg_trigger_plugin_hook('get_list', 'default_widgets', null, array());

// default to something if we can
if (!$widget_context && $list) {
	$widget_context = $list[0]['widget_context'];
}

$current_info = null;
$tabs = array();
foreach ($list as $info) {
	$url = "admin/appearance/default_widgets?widget_context={$info['widget_context']}";
	$selected = false;
	if ($widget_context == $info['widget_context']) {
		$selected = true;
		$current_info = $info;
	}

	$tabs[] = array(
		'title' => $info['name'],
		'url' => $url,
		'selected' => $selected
	);
}

$tabs_vars = array(
	'tabs' => $tabs
);

echo elgg_view('navigation/tabs', $tabs_vars);

echo elgg_view('output/longtext', array('value' => elgg_echo('admin:appearance:default_widgets:instructions')));

if (!$current_info) {
	$content = elgg_echo('admin:appearance:default_widgets:unknown_type');
} else {
	// default widgets are owned and saved to the site.
	elgg_set_page_owner_guid(elgg_get_config('site_guid'));
	elgg_push_context($current_info['widget_context']);

	$default_widgets_input = elgg_view('input/hidden', array(
		'name' => 'default_widgets',
		'value' => 1
	));

	$params = array(
		'content' => $default_widgets_input,
		'num_columns' => $current_info['widget_columns'],
	);

	$content = elgg_view_layout('widgets', $params);
	elgg_pop_context();
}
elgg_pop_context();

echo $content;