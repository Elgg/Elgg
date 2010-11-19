<?php
/**
 * Elgg widget wrapper
 *
 * @package Elgg
 * @subpackage Core
 */

$widget = $vars['entity'];
if (!elgg_instanceof($widget, 'object', 'widget')) {
	return true;
}

// @todo catch for disabled plugins
$widgettypes = elgg_get_widget_types('all');

$handler = $widget->handler;

$title = $widget->title;
if (!$title) {
	$title = $widgettypes[$handler]->name;
}

$can_edit = $widget->canEdit();

?>
<div class="widget draggable" id="widget_<?php echo $widget->guid; ?>">
	<div class="widget_title drag_handle">
		<h3><?php echo $title; ?></h3>
		<?php
		if ($can_edit) {
			echo elgg_view('widgets/controls', array('widget' => $widget));
		}
		?>
	</div>
	<?php
	if ($can_edit) {
		echo elgg_view('widgets/settings', array('widget' => $widget));
	}
	?>
	<div class="widget_content">
		<?php echo elgg_view("widgets/$handler/view", $vars); ?>
	</div>
</div>
