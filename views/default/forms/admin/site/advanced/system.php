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
	
	// cloud storage for user data
	$label = elgg_echo('admin:settings:user_data:data_store');
	$user_data_store_input = elgg_view('input/dropdown', [
		'name' => 'user_data_store',
		'options_values' => [
			'data_dir' => elgg_echo('admin:settings:user_data:data_dir'),
			'aws_s3' => elgg_echo('admin:settings:user_data:aws_s3')
		],
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
	
	<div class="hidden elgg-settings-user-data-store-info" id="elgg-settings-user-data-store-data-dir">
		<p class="elgg-text-help"><?php echo elgg_echo('admin:settings:user_data:data_dir:info'); ?></p>
	</div>
	
	<div class="hidden elgg-settings-user-data-store-info" id="elgg-settings-user-data-store-aws-s3">
		<p class="elgg-text-help"><?php echo elgg_echo('admin:settings:user_data:aws_s3:info'); ?></p>
		<?php
			$info = elgg_get_config('user_data_store_info');
			$s3 = elgg_extract('aws_s3', $info, []);
			$key_label = elgg_echo('admin:settings:user_data:aws_s3:key');
			$key_input = elgg_view('input/text', [
				'name' => 'user_data_store_info[aws_s3][key]',
				'value' => elgg_extract('key', $s3)
			]);
			
			$secret_label = elgg_echo('admin:settings:user_data:aws_s3:secret');
			$secret_input = elgg_view('input/text', [
				'name' => 'user_data_store_info[aws_s3][secret]',
				'value' => elgg_extract('secret', $s3)
			]);
			
			$bucket_label = elgg_echo('admin:settings:user_data:aws_s3:bucket');
			$bucket_input = elgg_view('input/text', [
				'name' => 'user_data_store_info[aws_s3][bucket]',
				'value' => elgg_extract('bucket', $s3)
			]);
			
			?>
			<div>
				<label>
				<?php
					echo $key_label;
					echo $key_input;
				?>
				</label>
			</div>
		
			<div>
				<label>
				<?php
					echo $secret_label;
					echo $secret_input;
				?>
				</label>
			</div>

			<div>
				<label>
				<?php
					echo $bucket_label;
					echo $bucket_input;
				?>
				</label>
			</div>
	</div>
</fieldset>
