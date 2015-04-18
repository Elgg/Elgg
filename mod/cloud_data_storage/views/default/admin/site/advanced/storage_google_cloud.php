<?php
/**
 * Settings for Google cloud storage
 */

$info = elgg_get_config('user_data_store_info');
$google_cloud = $info ? elgg_extract('google_cloud', $info, []) : [];

$account_name_label = elgg_echo('admin:settings:user_data_store:google_cloud:account_name');
$account_name_input = elgg_view('input/text', [
	'name' => 'user_data_store_info[google_cloud][account_name]',
	'value' => elgg_extract('account_name', $google_cloud)
]);

$key_label = elgg_echo('admin:settings:user_data_store:google_cloud:p12_key_location');
$key_input = elgg_view('input/text', [
	'name' => 'user_data_store_info[google_cloud][p12_key_location]',
	'value' => elgg_extract('p12_key_location', $google_cloud)
]);


$bucket_label = elgg_echo('admin:settings:user_data_store:google_cloud:bucket');
$bucket_input = elgg_view('input/text', [
	'name' => 'user_data_store_info[google_cloud][bucket]',
	'value' => elgg_extract('bucket', $google_cloud)
]);

?>
<p class="elgg-text-help"><?php echo elgg_echo('admin:settings:user_data_store:google_cloud:info'); ?></p>
<div>
	<label>
		<?php
		echo $account_name_label;
		echo $account_name_input;
		?>
	</label>
	<p class="elgg-text-help"><?php echo elgg_echo('admin:settings:user_data_store:google_cloud:account_name:info'); ?></p>
</div>

<div>
	<label>
		<?php
		echo $key_label;
		echo $key_input;
		?>
	</label>
	<p class="elgg-text-help"><?php echo elgg_echo('admin:settings:user_data_store:google_cloud:p12_key_location:info'); ?></p>
</div>

<div>
	<label>
		<?php
		echo $bucket_label;
		echo $bucket_input;
		?>
	</label>
</div>