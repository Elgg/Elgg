<?php
/**
 * Widgets CSS
 */

elgg_register_event_handler('view', 'widgets/friends/content', 'css_widget_content');
elgg_register_event_handler('view', 'widgets/friends/edit', 'css_widget_content');
elgg_register_event_handler('permissions_check', 'all', 'css_permissions_override', 600);

/**
 * Create dummy content
 *
 * @return string
 */
function css_widget_content() {
	return elgg_view('theme_sandbox/demo/ipsum');
}

/**
 * Give permissions
 *
 * @return true
 */
function css_permissions_override() {
	return true;
}

$w = [];
for ($i = 1; $i <= 6; $i++) {
	$obj = new ElggWidget();
	$obj->handler = 'friends';
	$obj->title = "Widget {$i}";
	$w[] = $obj;
}

$column1 = [$w[0], $w[1]];
$column2 = [$w[2], $w[3]];
$column3 = [$w[4], $w[5]];
$widgets = [1 => $column1, 2 => $column2, 3 => $column3];
$num_columns = 3;

echo '<div class="elgg-layout-widgets">';
echo '<div class="elgg-widgets-grid">';
for ($column_index = 1; $column_index <= $num_columns; $column_index++) {
	$column_widgets = $widgets[$column_index];

	echo "<div class='elgg-widgets elgg-widget-col-{$column_index}' data-widget-column='{$column_index}'>";
	if (is_array($column_widgets) && count($column_widgets) > 0) {
		foreach ($column_widgets as $widget) {
			echo elgg_view_entity($widget);
		}
	}
	
	echo '</div>';
}

echo '</div>';
echo '</div>';

?>
<script type='module'>
import 'jquery';
import elgg from 'elgg';

// widgets do not have guids so we override the edit toggle and delete button
$(function() {
	$('.elgg-widget-edit-button').unbind('click')
		.on('click', function() {
			$(this).closest('.elgg-module-widget').find('.elgg-widget-edit').slideToggle('medium');
			return false;
		});
	$('.elgg-widget-delete-button').on('click', function() {
		$(this).closest('.elgg-module-widget').remove();
		return false;
	});
});
</script>
