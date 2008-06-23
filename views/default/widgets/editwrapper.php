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

?>

<form id="widgetform<?php echo $vars['entity']->getGUID(); ?>" action="<?php echo $vars['url']; ?>action/widgets/save" method="post">

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
		<input type="hidden" name="guid" value="<?php echo $vars['entity']->getGUID(); ?>" />
		<input type="submit" value="<?php

			echo elgg_echo('save');			
		
		?>" />
	</p>

</form>

<script type="text/javascript">
$(document).ready(function() {

	$("#widgetform<?php echo $vars['entity']->getGUID(); ?>").submit(function () {
	
		var variables = $("#widgetform<?php echo $vars['entity']->getGUID(); ?>").serialize();
		$.post($("#widgetform<?php echo $vars['entity']->getGUID(); ?>").attr("action"),variables,function() {
			$("#widget<?php echo $vars['entity']->getGUID(); ?>").load("<?php echo $vars['url']; ?>pg/view/<?php echo $vars['entity']->getGUID(); ?>?shell=no&username=<?php echo page_owner_entity()->username; ?>&context=<?php echo get_context(); ?>");
		});
		return false;
	
	});

}); 
</script>