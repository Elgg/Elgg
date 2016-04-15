<?php
/**
 * Elgg widgets layout
 *
 * @uses $vars['content']          Optional display box at the top of layout
 * @uses $vars['num_columns']      Number of widget columns for this layout (3)
 * @uses $vars['show_add_widgets'] Display the add widgets button and panel (true)
 * @uses $vars['exact_match']      Widgets must match the current context (false)
 * @uses $vars['show_access']      Show the access control (true)
 * @uses $vars['owner_guid']       Widget owner GUID (optional, defaults to page owner GUID)
 */

$num_columns = elgg_extract('num_columns', $vars, 3);
$show_add_widgets = elgg_extract('show_add_widgets', $vars, true);
$exact_match = elgg_extract('exact_match', $vars, false);
$show_access = elgg_extract('show_access', $vars, true);
$owner_guid = elgg_extract('owner_guid', $vars);

$page_owner = elgg_get_page_owner_entity();
if ($owner_guid) {
	$owner = get_entity($owner_guid);
} else {
	$owner = $page_owner;
}

if (!$owner) {
	elgg_log('Can not resolve owner entity for widget layout', 'ERROR');
	return;
}

// Underlying views and functions assume that the page owner is the owner of the widgets
if ($owner->guid != $page_owner->guid) {
	elgg_set_page_owner_guid($owner->guid);
}

$context = elgg_get_context();

$widget_types = elgg_get_widget_types([
	'context' => $context,
	'exact' => $exact_match,
	'container' => $owner,
]);

elgg_push_context('widgets');

$widgets = elgg_get_widgets($owner->guid, $context);

$layout_attrs = elgg_format_attributes(array(
	'class' => 'elgg-layout-widgets',
	'data-page-owner-guid' => $owner->guid,
));

echo "<div $layout_attrs>";

if (elgg_can_edit_widget_layout($context)) {
	if ($show_add_widgets) {
		echo elgg_view('page/layouts/widgets/add_button');
	}
	$params = array(
		'widgets' => $widgets,
		'context' => $context,
		'exact_match' => $exact_match,
		'show_access' => $show_access,
	);
	echo elgg_view('page/layouts/widgets/add_panel', $params);
}

if (isset($vars['content'])) {
	echo $vars['content'];
}

$widget_class = "elgg-col-1of{$num_columns}";
for ($column_index = 1; $column_index <= $num_columns; $column_index++) {
	if (isset($widgets[$column_index])) {
		$column_widgets = $widgets[$column_index];
	} else {
		$column_widgets = array();
	}

	echo "<div class=\"$widget_class elgg-widgets\" id=\"elgg-widget-col-$column_index\">";
	if (sizeof($column_widgets) > 0) {
		foreach ($column_widgets as $widget) {
			if (array_key_exists($widget->handler, $widget_types)) {
				echo elgg_view_entity($widget, array('show_access' => $show_access));
			}
		}
	}
	echo '</div>';
}

elgg_pop_context();

echo elgg_view('graphics/ajax_loader', array('id' => 'elgg-widget-loader'));

echo '</div>';


// Restore original page owner
if ($owner->guid != $page_owner->guid) {
	elgg_set_page_owner_guid($page_owner->guid);
}