<?php

	/**
	 * Elgg edit widget layout
	 * 
	 * @package Elgg
	 * @subpackage Core

	 * @author Curverider Ltd

	 * @link http://elgg.org/
	 */

	$guid = $vars['entity']->getGUID();

	
	$form_body = $vars['body'];
	$form_body .= "<p><label>" . elgg_echo('access') . ": " . elgg_view('input/access', array('internalname' => 'params[access_id]','value' => $vars['entity']->access_id)) . "</label></p>";
	$form_body .= "<p>" . elgg_view('input/hidden', array('internalname' => 'guid', 'value' => $guid)) . elgg_view('input/hidden', array('internalname' => 'noforward', 'value' => 'true')) . elgg_view('input/submit', array('internalname' => "submit$guid", 'value' => elgg_echo('save'))) . "</p>";

	echo elgg_view('input/form', array('internalid' => "widgetform$guid", 'body' => $form_body, 'action' => "{$vars['url']}action/widgets/save"))	
?>


<script type="text/javascript">
$(document).ready(function() {

	$("#widgetform<?php echo $guid; ?>").submit(function () {
	
		$("#submit<?php echo $guid; ?>").attr("disabled","disabled");
		$("#submit<?php echo $guid; ?>").attr("value","<?php echo elgg_echo("saving"); ?>");
		$("#widgetcontent<?php echo $guid; ?>").html('<?php echo elgg_view('ajax/loader',array('slashes' => true)); ?>');
		$("#widget<?php echo $guid; ?> .toggle_box_edit_panel").click();
	
		var variables = $("#widgetform<?php echo $guid; ?>").serialize();
		$.post($("#widgetform<?php echo $guid; ?>").attr("action"),variables,function() {
			$("#submit<?php echo $guid; ?>").attr("disabled","");
			$("#submit<?php echo $guid; ?>").attr("value","<?php echo elgg_echo("save"); ?>");
			$("#widgetcontent<?php echo $guid; ?>").load("<?php echo $vars['url']; ?>pg/view/<?php echo $guid; ?>?shell=no&username=<?php echo page_owner_entity()->username; ?>&context=<?php echo get_context(); ?>&callback=true");
		});
		return false;
	
	});

}); 
</script>