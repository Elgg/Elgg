<?php
/**
 * Elgg widgets layout
 *
 * @uses $vars['content']          Optional display box at the top of layout
 * @uses $vars['num_columns']      Number of widget columns for this layout (3)
 * @uses $vars['show_add_widgets'] Display the add widgets button and panel (true)
 * @uses $vars['exact_match']      Widgets must match the current context (false)
 * @uses $vars['show_access']      Show the access control (true)
 */

$num_columns = elgg_extract('num_columns', $vars, 3);
$show_add_widgets = elgg_extract('show_add_widgets', $vars, true);
$exact_match = elgg_extract('exact_match', $vars, false);
$show_access = elgg_extract('show_access', $vars, true);

$owner = elgg_get_page_owner_entity();

$widget_types = elgg_get_widget_types();

$context = elgg_get_context();
elgg_push_context('widgets');

$widgets = elgg_get_widgets($owner->guid, $context);

if (elgg_can_edit_widget_layout($context)) {
	if ($show_add_widgets) {
		echo elgg_view('page/layouts/widgets/add_button');
	}
	$params = array(
		'widgets' => $widgets,
		'context' => $context,
		'exact_match' => $exact_match,
	);
	echo elgg_view('page/layouts/widgets/add_panel', $params);
}

echo $vars['content'];

$widget_class = "elgg-col-1of{$num_columns}";
for ($column_index = 1; $column_index <= $num_columns; $column_index++) {
	$column_widgets = $widgets[$column_index];

	echo "<div class=\"$widget_class elgg-widgets\" id=\"elgg-widget-col-$column_index\">";
	if (is_array($column_widgets) && sizeof($column_widgets) > 0) {
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
