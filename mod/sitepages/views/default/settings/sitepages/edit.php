<p>
	<?php echo elgg_echo('sitepages:ownfront'); ?>
	<select name="params[ownfrontpage]">
		<option value="yes" <?php if ($vars['entity']->ownfrontpage == 'yes') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:yes'); ?></option>
		<option value="no" <?php if ($vars['entity']->ownfrontpage != 'yes') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:no'); ?></option>
	</select>
</p>