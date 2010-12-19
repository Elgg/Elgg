<?php

$widgets = $vars['widgets'];
$context = $vars['context'];

$widget_types = elgg_get_widget_types($context);

$current_handlers = array();
foreach ($widgets as $column_widgets) {
	foreach ($column_widgets as $widget) {
		$current_handlers[] = $widget->handler;
	}
}

?>
<div class="elgg-widgets-add-panel hidden" id="elgg-togglee-add-widgets">
	<p>
		<?php echo elgg_echo('widgets:add:description'); ?>
	</p>
	<ul>
<?php
		foreach ($widget_types as $handler => $widget_type) {
			$id = "elgg-widget-type-$handler";
			// check if widget added and only one instance allowed
			if ($widget_type->multiple == false && in_array($handler, $current_handlers)) {
				$class = 'elgg-widget-unavailable';
				$tooltip = elgg_echo('widget:unavailable');
			} else {
				$class = 'elgg-widget-available';
				$tooltip = $widget_type->description;
			}

			if ($widget_type->multiple) {
				$class .= ' elgg-widget-multiple';
			} else {
				$class .= ' elgg-widget-single';
			}

			echo "<li title=\"$tooltip\" id=\"$id\" class=\"$class\">$widget_type->name</li>";
		}
?>
	</ul>
<?php
	$params = array(
		'internalname' => 'widget_context',
		'value' => $context
	);
	echo elgg_view('input/hidden', $params);
?>
	<div class="clearfloat"></div>
</div>
