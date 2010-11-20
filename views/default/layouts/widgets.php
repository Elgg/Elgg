<?php
/**
 * Elgg widgets layout
 *
 * @uses $vars['box'] Optional display box at the top of layout
 * @uses $vars['num_columns'] Number of widget columns for this layout
 * @uses $vars['show_add_widgets'] Display the add widgets button
 */

$box = elgg_get_array_value('box', $vars, '');
$num_columns = elgg_get_array_value('num_columns', $vars, 3);
$show_add_widgets = elgg_get_array_value('show_add_widgets', $vars, true);

$owner = elgg_get_page_owner();
$context = elgg_get_context();
elgg_push_context('widgets');

$widgets = elgg_get_widgets($owner->guid, $context);

if (elgg_can_edit_widget_layout($context)) {
	if ($show_add_widgets) {
		echo elgg_view('widgets/add_button');
	}
	$params = array(
		'widgets' => $widgets,
		'context' => $context,
	);
	echo elgg_view('widgets/add_panel', $params);
}

echo '<div class="widget_layout">';

echo $vars['box'];

$widget_class = "widget_{$num_columns}_columns";
for ($column_index = 1; $column_index <= $num_columns; $column_index++) {
	$column_widgets = $widgets[$column_index];

	echo "<div class=\"widget_column $widget_class\" id=\"widget_col_$column_index\">";
	if (is_array($column_widgets) && sizeof($column_widgets) > 0) {
		foreach ($column_widgets as $widget) {
			echo elgg_view_entity($widget);
		}
	}
	echo '</div>';
}

echo '<div class="clearfloat"></div>';
echo '</div>';

elgg_pop_context();