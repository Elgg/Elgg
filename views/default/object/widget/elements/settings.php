<?php
/**
 * Elgg widget edit settings
 *
 * @uses $vars['widget']
 */

$widget = elgg_extract('widget', $vars);

// not using elgg_view_form() so that we can detect if the form is empty
$form_body = elgg_view('forms/widgets/save', $vars);
if (!$form_body) {
	return true;
}

$form = elgg_view('input/form', array(
	'action' => 'action/widgets/save',
	'body' => $form_body,
	'class' => 'elgg-form-widgets-save',
));
?>

<div class="elgg-widget-edit" id="widget-edit-<?php echo $widget->guid; ?>">
	<?php echo $form; ?>
</div>
