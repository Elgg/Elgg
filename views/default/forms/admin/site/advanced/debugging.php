<?php
/**
 * Advanced site settings, debugging section.
 */

$debug_options = array(
	'0' => elgg_echo('installation:debug:none'),
	'ERROR' => elgg_echo('installation:debug:error'),
	'WARNING' => elgg_echo('installation:debug:warning'),
	'NOTICE' => elgg_echo('installation:debug:notice'),
	'INFO' => elgg_echo('installation:debug:info'),
);

$debug_label = elgg_echo('installation:debug:label');
$debug_input = elgg_view('input/select', array(
	'options_values' => $debug_options,
	'name' => 'debug',
	'value' => elgg_get_config('debug'),
));

?>
<fieldset class="elgg-fieldset" id="elgg-settings-advanced-debugging">
	<legend><?php echo elgg_echo('admin:legend:debug'); ?></legend>
	
	<div>
		<p><?php echo elgg_echo('installation:debug'); ?></p>
		
		<label>
			<?php 
			echo $debug_label; 
			echo $debug_input;
			?>
			
		</label>
	</div>
</fieldset>
