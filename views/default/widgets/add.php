<?php

$widgets = $vars['widgets'];
$widget_types = elgg_get_widget_types();

$context = $vars['context'];

?>
<div class="widgets_add hidden">
	<p>
		<?php echo elgg_echo('widgets:add:description'); ?>
	</p>
	<ul>
<?php
		foreach ($widget_types as $handler => $widget_type) {
			$options = array(
				'text' => $widget_type->name,
				'href' => '#',
				'internalid' => $handler,
			);
			$link = elgg_view('output/url', $options);
			echo "<li>$link</li>";
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
