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

$access_label = elgg_echo('access');
$access = elgg_view('input/access', array('internalname' => 'params[access_id]','value' => $widget->access_id));
$access_html = "<p><label>$access_label:</label> $access</p>";

$hidden = elgg_view('input/hidden', array('internalname' => 'guid', 'value' => $widget->guid));
$submit = elgg_view('input/submit', array('value' => elgg_echo('save')));

// dashboard widgets do not get access controls
if (elgg_in_context('dashboard')) {
	$access = '';
}

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
