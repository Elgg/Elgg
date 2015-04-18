<?php
/**
 * Settings for MS Azure cloud storage
 */

$info = elgg_get_config('user_data_store_info');
$azure = $info ? elgg_extract('ms_azure', $info, []) : [];
$endpoint_label = elgg_echo('admin:settings:user_data_store:ms_azure:endpoint');
$endpoint_input = elgg_view('input/text', [
	'name' => 'user_data_store_info[ms_azure][endpoint]',
	'value' => elgg_extract('endpoint', $azure)
]);

$account_label = elgg_echo('admin:settings:user_data_store:ms_azure:account');
$account_input = elgg_view('input/text', [
	'name' => 'user_data_store_info[ms_azure][account]',
	'value' => elgg_extract('account', $azure)
]);

$key_label = elgg_echo('admin:settings:user_data_store:ms_azure:key');
$key_input = elgg_view('input/text', [
	'name' => 'user_data_store_info[ms_azure][key]',
	'value' => elgg_extract('key', $azure)
]);

$container_label = elgg_echo('admin:settings:user_data_store:ms_azure:container');
$container_input = elgg_view('input/text', [
	'name' => 'user_data_store_info[ms_azure][container]',
	'value' => elgg_extract('container', $azure)
]);

?>
<p class="elgg-text-help"><?php echo elgg_echo('admin:settings:user_data_store:ms_azure:info'); ?></p>
<div>
	<label>
		<?php
		echo $endpoint_label;
		echo $endpoint_input;
		?>
	</label>
	<p class="elgg-text-help">
		<?php
		echo elgg_echo('admin:settings:user_data_store:ms_azure:endpoint_help');
		?>
	</p>
</div>

<div>
	<label>
		<?php
		echo $account_label;
		echo $account_input;
		?>
	</label>
</div>

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
		echo $container_label;
		echo $container_input;
		?>
	</label>
</div>