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
	<?php }
	
	// data storage settings
	$options = [
		'data_dir' => 'forms/admin/site/advanced/storage_data_dir'
	];
	
	$options = elgg_trigger_plugin_hook('config', 'user_data_storage_options', [], $options);
	$option_views = '';
	
	foreach ($options as $name => $view) {
		$options_values[$name] = elgg_echo("admin:settings:user_data_store:$name");
		
		$name_id = str_replace([' ', '_'], '-', $name);
		$id = "elgg-settings-user-data-store-$name_id";
		$option_views .= "<div class=\"hidden elgg-settings-user-data-store-info\" id=\"$id\">";
		$option_views .= elgg_view($view);
		$option_views .= "</div>";
	}
	
	$label = elgg_echo('admin:settings:user_data_store:data_store');
	$user_data_store_input = elgg_view('input/dropdown', [
		'name' => 'user_data_store',
		'options_values' => $options_values,
		'value' => elgg_get_config('user_data_store'),
		'id' => 'elgg-settings-user-data-store'
	]);
	?>
	
	<label>
		<?php
			echo $label;
			echo $user_data_store_input;
		?>
	</label>
	
	<?php echo $option_views; ?>
</fieldset>
