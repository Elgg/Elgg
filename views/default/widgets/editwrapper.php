<?php
/**
 * Elgg widget edit settings
 *
 * @package Elgg
 * @subpackage Core
 */

$widget = $vars['widget'];

$edit_view = "widgets/$widget->handler/edit";
$custom_form_section = elgg_view($edit_view, array('entity' => $widget));

$access = elgg_view('input/access', array('internalname' => 'params[access_id]','value' => $widget->access_id));
$hidden = elgg_view('input/hidden', array('internalname' => 'guid', 'value' => $widget->guid)) .
$hidden .= elgg_view('input/hidden', array('internalname' => 'noforward', 'value' => 'true'));
$submit = elgg_view('input/submit', array('internalname' => "submit$guid", 'value' => elgg_echo('save')));

$body = <<<___END
	$custom_form_section
	<p>
		<label>Access:</label> $access
	</p>
	<p>
		$hidden
		$submit
	</p>
___END;

?>
<div class="widget_edit">
<?php
$params = array(
	'body' => $body,
	'action' => "action/widgets/save",
	'internalid' => "widgetform$guid"
);
echo elgg_view('input/form', $params);
// _<?php echo $widget->guid; 
?>
</div>
