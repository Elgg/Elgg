<?php

$plugin = $vars['entity'];

?>

<div>
	<label for="thewire-limit"><?php echo elgg_echo('thewire:settings:limit'); ?></label><br/>
	<input type="number" name="params[limit]" value="<?php echo $plugin->limit; ?>" id="thewire-limit" list="thewire-limit-list" />
	<datalist id="thewire-limit-list">
		<option>0</option>
		<option>140</option>
		<option>256</option>
	</datalist>
</div>