<?php
?>
<p>
	<?php echo elgg_echo('riverdashboard:useasdashboard'); ?>
	<select name="params[useasdashboard]">
		<option value="yes" <?php if ($vars['entity']->useasdashboard == 'yes') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:yes'); ?></option>
		<option value="no" <?php if ($vars['entity']->useasdashboard != 'yes') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:no'); ?></option>
	</select>
</p>
<p>
	<?php echo elgg_echo('riverdashboard:avataricon'); ?>
	<select name="params[avatar_icon]">
		<option value="icon" <?php if ($vars['entity']->avatar_icon == 'icon') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:icon'); ?></option>
		<option value="avatar" <?php if ($vars['entity']->avatar_icon == 'avatar') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:avatar'); ?></option>
	</select>
</p>