<?php
/**
 * Settings for AWS S3 cloud storage
 */

$info = elgg_get_config('user_data_store_info');
$s3 = $info ? elgg_extract('aws_s3', $info, []) : [];
$key_label = elgg_echo('admin:settings:user_data_store:aws_s3:key');
$key_input = elgg_view('input/text', [
	'name' => 'user_data_store_info[aws_s3][key]',
	'value' => elgg_extract('key', $s3)
]);

$secret_label = elgg_echo('admin:settings:user_data_store:aws_s3:secret');
$secret_input = elgg_view('input/text', [
	'name' => 'user_data_store_info[aws_s3][secret]',
	'value' => elgg_extract('secret', $s3)
]);

$bucket_label = elgg_echo('admin:settings:user_data_store:aws_s3:bucket');
$bucket_input = elgg_view('input/text', [
	'name' => 'user_data_store_info[aws_s3][bucket]',
	'value' => elgg_extract('bucket', $s3)
]);
?>
<p class="elgg-text-help"><?php echo elgg_echo('admin:settings:user_data_store:aws_s3:info'); ?></p>
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