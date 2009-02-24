<?php
?>
<p>
	<?php echo elgg_echo('riverdashboard:useasdashboard'); ?>
	
	<select name="params[useasdashboard]">
		<option value="yes" <?php if ($vars['entity']->useasdashboard == 'yes') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:yes'); ?></option>
		<option value="no" <?php if ($vars['entity']->useasdashboard != 'yes') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:no'); ?></option>
	</select>
	
</p>
