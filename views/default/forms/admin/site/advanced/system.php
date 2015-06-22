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
		];
		if ($field === 'wwwroot') {
			$params['value'] = elgg_get_site_entity()->getStoredURL();
		} else {
			$params['value'] = elgg_get_config($field);
		}

		if (in_array($field, ['dataroot', 'wwwroot']) && elgg_get_config("{$field}_in_settings")) {
			$params['readonly'] = true;
			$params['class'] = 'elgg-state-disabled';
			$warning = elgg_echo('admin:settings:in_settings_file');
		}

		if ($warning) {
			$input = "<span class=\"elgg-text-help\">$warning</span>";
		} else {
			$input = elgg_view("input/text", $params);
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
