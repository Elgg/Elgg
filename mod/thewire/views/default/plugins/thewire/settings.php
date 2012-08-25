<p>
  <?php echo elgg_echo('thewire:settings:limit'); ?>
 
  <select name="params[limit]">
  	<option value="0" <?php if ($vars['entity']->limit == 0) echo 'selected = "selected"'; ?>><?php echo elgg_echo('thewire:settings:limit:none'); ?></option>
  	<option value="140" <?php if ($vars['entity']->limit == 140) echo 'selected = "selected"'; ?>>140</option>
  	<option value="256" <?php if ($vars['entity']->limit == 256) echo 'selected = "selected"'; ?>>256</option>
  </select>
</p>