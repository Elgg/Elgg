<?php

$widgets = $vars['widgets'];
$widget_types = get_widget_types();

?>
<div class="widgets_add hidden">
	<p>
		<?php echo elgg_echo('widgets:add:description'); ?>
	</p>
	<ul>
		<?php
			foreach ($widget_types as $widget_type) {
				$link = elgg_view('output/url', array('text' => $widget_type->name, 'href' => '#'));
				echo "<li>$link</li>";
			}
		?>
	</ul>
	<div class="clearfloat"></div>
</div>
