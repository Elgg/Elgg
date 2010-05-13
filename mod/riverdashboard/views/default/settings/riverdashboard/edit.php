
<p>
	<?php echo elgg_echo('river:type'); ?>
	<select name="params[activitytype]">
		<option value="classic" <?php if ($vars['entity']->activitytype == 'classic') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('river:classic'); ?></option>
		<option value="clustered" <?php if ($vars['entity']->activitytype == 'clustered') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('river:clustered'); ?></option>
	</select>
</p>