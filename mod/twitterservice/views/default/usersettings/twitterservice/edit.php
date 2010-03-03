<p>
	<?php echo elgg_echo('twitterservice:twittername'); ?> <?php echo elgg_view('input/text', array('internalname' => 'params[twittername]', 'value' => $vars['entity']->twittername)); ?>
</p>
<p>
	<?php echo elgg_echo('twitterservice:twitterpass'); ?> <?php echo elgg_view('input/password', array('internalname' => 'params[twitterpass]', 'value' => $vars['entity']->twitterpass)); ?>
</p>
<?php if (is_plugin_enabled('thewire')) { ?>
<p>
	<?php echo elgg_echo('twitterservice:postwire'); ?>
	
	<select name="params[sendtowire]">
		<option value="yes" <?php if ($vars['entity']->sendtowire == 'yes') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:yes'); ?></option>
		<option value="no" <?php if ($vars['entity']->sendtowire != 'yes') echo " selected=\"yes\" "; ?>><?php echo elgg_echo('option:no'); ?></option>
	</select>
	
</p>
<?php } ?>