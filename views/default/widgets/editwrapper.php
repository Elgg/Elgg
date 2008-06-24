<?php

	/**
	 * Elgg edit widget layout
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	$guid = $vars['entity']->getGUID();

?>

<form id="widgetform<?php echo $guid; ?>" action="<?php echo $vars['url']; ?>action/widgets/save" method="post">

	<?php

		echo $vars['body'];
	
	?>

	<p>
		<label>
			<?php echo elgg_echo('access'); ?>:
			<?php echo elgg_view('input/access', array('internalname' => 'params[access_id]','value' => $vars['entity']->access_id)); ?>
		</label>
	</p>
	<p>
		<input type="hidden" name="guid" value="<?php echo $guid; ?>" />
		<input type="submit" id="submit<?php echo $guid; ?>" value="<?php

			echo elgg_echo('save');			
		
		?>" />
	</p>

</form>

<script type="text/javascript">
$(document).ready(function() {

	$("#widgetform<?php echo $guid; ?>").submit(function () {
	
		$("#submit<?php echo $guid; ?>").attr("disabled","disabled");
		$("#submit<?php echo $guid; ?>").attr("value","<?php echo elgg_echo("saving"); ?>");
		$("#widgetcontent<?php echo $guid; ?>").html('<?php echo elgg_view('ajax/loader',array('slashes' => true)); ?>');
	
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