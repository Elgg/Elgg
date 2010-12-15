<?php
/**
 * Widget object
 *
 * @uses $vars['entity']
 */

$widget = $vars['entity'];
if (!elgg_instanceof($widget, 'object', 'widget')) {
	return true;
}

// @todo catch for disabled plugins
$widgettypes = elgg_get_widget_types('all');

$handler = $widget->handler;

$title = $widget->getTitle();

$can_edit = $widget->canEdit();

$widget_id = "widget-$widget->guid";
$widget_instance = "widget-instance-$handler";

?>
<div class="widget draggable <?php echo $widget_instance?>" id="<?php echo $widget_id; ?>">
	<div class="widget-title drag-handle">
		<h3><?php echo $title; ?></h3>
	</div>
	<?php
	if ($can_edit) {
		echo elgg_view('layout/objects/widget/controls', array('widget' => $widget));
	}
	?>
	<div class="widget-container">
		<?php
		if ($can_edit) {
			echo elgg_view('layout/objects/widget/settings', array('widget' => $widget));
		}
		?>
		<div class="widget-content">
			<?php
			if (elgg_view_exists("widgets/$handler/content")) {
				echo elgg_view("widgets/$handler/content", $vars);
			} else {
				elgg_deprecated_notice("widgets use content as the display view", 1.8);
				echo elgg_view("widgets/$handler/view", $vars);				
			}
			?>
		</div>
	</div>
</div>
