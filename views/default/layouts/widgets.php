<?php
/**
 * Elgg widgets layout
 *
 * @uses $vars['box'] Optional display box at the top of layout
 * @uses $vars['num_columns'] Number of widget columns for this layout
 */

$owner = elgg_get_page_owner();
$context = elgg_get_context();
elgg_push_context('widgets');

if (isset($vars['box'])) {
	echo $vars['box'];
}

$num_columns = 3;
if (isset($vars['num_columns'])) {
	$num_columns = $vars['num_columns'];
}

$widget_class = "widget_col_$num_columns";
for ($column_index = 1; $column_index <= $num_columns; $column_index++) {
	$widgets = get_widgets($owner->guid, $context, $column_index);

	// test code during design and implementation
	$widget1 = new ElggWidget();
	$widget1->handler = 'test';
	$widget2 = new ElggWidget();
	$widget2->handler = 'test';
	$widgets = array($widget1, $widget2);

	$first = ($column_index == 1) ? 'widget_first_col' : '';

	echo "<div class=\"widget_column $widget_class $first\">";
	if (is_array($widgets) && sizeof($widgets) > 0) {
		foreach ($widgets as $widget) {
			echo elgg_view_entity($widget);
		}
	}
	echo '</div>';
}

elgg_pop_context();