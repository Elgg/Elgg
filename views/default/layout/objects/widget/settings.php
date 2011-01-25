<?php
/**
 * Elgg widget edit settings
 *
 * @uses $vars['widget']
 * @uses $vars['show_access']
 */

$widget = $vars['widget'];
$show_access = elgg_get_array_value('show_access', $vars, true);

$edit_view = "widgets/$widget->handler/edit";
$custom_form_section = elgg_view($edit_view, array('entity' => $widget));

$access = '';
if ($show_access) {
	$access = elgg_view('input/access', array(
		'internalname' => 'params[access_id]',
		'value' => $widget->access_id,
	));
}

if (!$custom_form_section && !$access) {
	return true;
}

$hidden = elgg_view('input/hidden', array('internalname' => 'guid', 'value' => $widget->guid));
$submit = elgg_view('input/submit', array('value' => elgg_echo('save')));

$body = <<<___END
	$custom_form_section
	$access
	<p>
		$hidden
		$submit
	</p>
___END;

?>
<div class="elgg-widget-edit" id="elgg-togglee-widget-<?php echo $widget->guid; ?>">
<?php
$params = array(
	'body' => $body,
	'action' => "action/widgets/save"
);
echo elgg_view('input/form', $params);
?>
</div>
