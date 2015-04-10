<?php
/**
 * Advanced site settings, system section.
 */
?>
<fieldset class="elgg-fieldset" id="elgg-settings-advanced-system">
	<legend><?php echo elgg_echo('admin:legend:system');?></legend>
	<?php foreach (['wwwroot', 'path', 'dataroot'] as $field) {
		$warning = false;
		$label = elgg_echo('installation:' . $field);

		$params = [
			'name' => $field,
			'value' => elgg_get_config($field)
		];
		if ($field == 'dataroot' && elgg_get_config('dataroot_in_settings')) {
			$params['readonly'] = true;
			$params['class'] = 'elgg-state-disabled';
			$warning = elgg_echo('admin:settings:in_settings_file');
		}

		$input = elgg_view("input/text", $params);
		if ($warning) {
			$input = "<span class=\"elgg-text-help\">$warning</span>";
		}
		
		?>
	<div>
		<label>
			<?php 
				echo $label; 
				echo $input;
			?>
		</label>
	</div>
	<?php } ?>
</fieldset>
