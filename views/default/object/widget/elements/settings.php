<?php
/**
 * Elgg widget edit settings
 *
 * @uses $vars['widget']
 */

$widget = elgg_extract('widget', $vars);
if (!$widget instanceof ElggWidget) {
	return;
}

$additional_class = preg_replace('/[^a-z0-9-]/i', '-', "elgg-form-widgets-save-{$widget->handler}");

$form = elgg_view_form('widgets/save', [
	'class' => [
		$additional_class,
	],
], $vars);

if (!$form) {
	return;
}

?>

<div class="elgg-widget-edit" id="widget-edit-<?php echo $widget->guid; ?>">
	<?php echo $form; ?>
</div>
