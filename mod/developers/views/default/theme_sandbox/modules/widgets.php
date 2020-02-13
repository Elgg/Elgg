<?php
/**
 * Widgets CSS
 */

elgg_register_plugin_hook_handler('view', 'widgets/friends/content', 'css_widget_content');
elgg_register_plugin_hook_handler('view', 'widgets/friends/edit', 'css_widget_content');
elgg_register_plugin_hook_handler('permissions_check', 'all', 'css_permissions_override', 600);

/**
 * Create dummy content
 *
 * @return string
 */
function css_widget_content() {
	return elgg_view('developers/ipsum');
}

/**
 * Give permissions
 *
 * @return true
 */
function css_permissions_override() {
	return true;
}

?>
<div class="elgg-body mal">
	<?php echo elgg_view('theme_sandbox/header', $vars); ?>
<?php
$w = [];
for ($i=1; $i<=6; $i++) {
	$obj = new ElggWidget();
	$obj->handler = 'friends';
	$obj->title = "Widget $i";
	$w[] = $obj;
}
$column1 = [$w[0], $w[1]];
$column2 = [$w[2], $w[3]];
$column3 = [$w[4], $w[5]];
$widgets = [1 => $column1, 2 => $column2, 3 => $column3];
$num_columns = 3;

echo '<div class="elgg-layout-widgets">';
echo '<div class="elgg-widgets-grid">';
$widget_class = "elgg-col-1of{$num_columns}";
for ($column_index = 1; $column_index <= $num_columns; $column_index++) {
	$column_widgets = $widgets[$column_index];

	echo "<div class=\"$widget_class elgg-widgets\" id=\"elgg-widget-col-$column_index\">";
	if (is_array($column_widgets) && sizeof($column_widgets) > 0) {
		foreach ($column_widgets as $widget) {
			echo elgg_view_entity($widget);
		}
	}
	echo '</div>';
}
echo '</div>';
echo '</div>';

?>
</div>
<script>
require(['elgg', 'jquery'], function (elgg, $) {
	// widgets do not have guids so we override the edit toggle and delete button
	$(function() {
		$('.elgg-widget-edit-button').unbind('click');
		$('.elgg-widget-edit-button').on('click', function() {
			$(this).closest('.elgg-module-widget').find('.elgg-widget-edit').slideToggle('medium');
			return false;
		});
		$('.elgg-widget-delete-button').on('click', function() {
			$(this).closest('.elgg-module-widget').remove();
			return false;
		});
	});
});
</script>
