<?php
/**
 * Elgg widgets layout
 *
 * @uses $vars['no_widgets']       Optional string or Closure that will be show if there are no widgets
 * @uses $vars['num_columns']      Number of widget columns for this layout (3)
 * @uses $vars['show_add_widgets'] Display the add widgets button and panel (true)
 * @uses $vars['show_access']      Show the access control (true)
 * @uses $vars['owner_guid']       Widget owner GUID (optional, defaults to page owner GUID)
 */

$num_columns = elgg_extract('num_columns', $vars, 3);
$show_add_widgets = elgg_extract('show_add_widgets', $vars, true);
$show_access = elgg_extract('show_access', $vars, true);
$owner_guid = elgg_extract('owner_guid', $vars);

$page_owner = elgg_get_page_owner_entity();
if ($owner_guid) {
	$owner = get_entity($owner_guid);
} else {
	$owner = $page_owner;
}

if (!$owner) {
	return;
}

elgg_require_js('elgg/widgets');

// Underlying views and functions assume that the page owner is the owner of the widgets
if ($owner->guid != $page_owner->guid) {
	elgg_set_page_owner_guid($owner->guid);
}

$context = elgg_get_context();

$widgets = elgg_get_widgets($owner->guid, $context);

$result = '';
$no_widgets = elgg_extract('no_widgets', $vars);
if (empty($widgets) && !empty($no_widgets)) {
	if ($no_widgets instanceof \Closure) {
		echo $no_widgets();
	} else {
		$result .= $no_widgets;
	}
}

if ($show_add_widgets && elgg_can_edit_widget_layout($context)) {
	$result .= elgg_view('page/layouts/widgets/add_button', $vars);
}

// push context after the add_button as add button uses current context
elgg_push_context('widgets');

if ($widgets) {
	$widget_types = elgg_get_widget_types([
		'context' => $context,
		'container' => $owner,
	]);
}

// move hidden columns widgets to last visible column
if (!isset($widgets[$num_columns])) {
	$widgets[$num_columns] = [];
}

foreach ($widgets as $index => $column_widgets) {
	if ($index <= $num_columns) {
		continue;
	}
		
	// append widgets to last column and retain order
	foreach ($column_widgets as $column_widget) {
		$widgets[$num_columns][] = $column_widget;
	}
	unset($widgets[$index]);
}

$grid = '';
for ($column_index = 1; $column_index <= $num_columns; $column_index++) {
	$column_widgets = (array) elgg_extract($column_index, $widgets, []);
	
	$widgets_content = '';
	foreach ($column_widgets as $widget) {
		if (!array_key_exists($widget->handler, $widget_types)) {
			continue;
		}
		
		$widgets_content .= elgg_view_entity($widget, [
			'show_access' => $show_access,
			'register_rss_link' => false,
		]);
	}

	$grid .= elgg_format_element('div', [
		'id' => "elgg-widget-col-{$column_index}",
		'class' => [
			'elgg-widgets',
		],
		
	], $widgets_content);
}

$result .= elgg_format_element('div', [
	'class' => 'elgg-widgets-grid',
], $grid);

elgg_pop_context();

echo elgg_format_element('div', [
	'class' => [
		'elgg-layout-widgets',
		"elgg-layout-widgets-{$context}",
	],
	'data-page-owner-guid' => $owner->guid,
], $result);

// Restore original page owner
if ($owner->guid != $page_owner->guid) {
	elgg_set_page_owner_guid($page_owner->guid);
}
