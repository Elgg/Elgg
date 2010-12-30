<?php
/**
 * Widgets CSS
 */

$title = 'Widgets';

require dirname(__FILE__) . '/head.php';

elgg_register_plugin_hook_handler('view', 'widgets/friends/content', 'css_widget_content');
elgg_register_plugin_hook_handler('view', 'widgets/friends/edit', 'css_widget_content');
elgg_register_plugin_hook_handler('permissions_check', 'all', 'css_permissions_override');

function css_widget_content() {
	global $ipsum;
	return $ipsum;
}

function css_permissions_override() {
	return true;
}


?>
<body>
	<div class="elgg-page" style="width: 800px; margin: 20px auto;">
		<h1 class="mbl"><?php echo $title; ?></h1>
		<div class="mbl"><a href="index.php">return to index</a></div>
<?php
$w = array();
for ($i=1; $i<=6; $i++) {
	$obj = new ElggWidget();
	$obj->handler = 'friends';
	$obj->title = "Widget $i";
	$w[] = $obj;
}
$column1 = array($w[0], $w[1]);
$column2 = array($w[2], $w[3]);
$column3 = array($w[4], $w[5]);
$widgets = array(1 => $column1, 2 => $column2, 3 => $column3);
$num_columns = 3;
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
?>
	</div>
</body>
</html>