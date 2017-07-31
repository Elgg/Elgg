<?php
/**
 * Server PHP info
 */

$php_log = ini_get('error_log');
if (!$php_log) {
	$php_log = elgg_echo('admin:server:error_log');
}

$post_max_size = elgg_get_ini_setting_in_bytes('post_max_size');
$upload_max_filesize = elgg_get_ini_setting_in_bytes('upload_max_filesize');

$post_max_size_warning = '';
if ($upload_max_filesize > $post_max_size) {
	// @todo show a link to something like http://nigel.mcnie.name/blog/uploadmaxfilesizepostmaxsize-experimentation ?
	$post_max_size_warning = elgg_echo('admin:server:warning:post_max_too_small');
}

?>
<table class="elgg-table-alt">
	<tr>
		<td><b><?= elgg_echo('admin:server:label:php_version'); ?></b></td>
		<td><?= phpversion(); ?></td>
	</tr>
	<tr>
		<td><b><?= elgg_echo('admin:server:label:php_ini'); ?></b></td>
		<td><?= php_ini_loaded_file(); ?></td>
	</tr>
	<tr>
		<td><b><?= elgg_echo('admin:server:label:php_log'); ?></b></td>
		<td><?= $php_log; ?></td>
	</tr>
	<tr>
		<td><b><?= elgg_echo('admin:server:label:mem_avail'); ?></b></td>
		<td><?= number_format(elgg_get_ini_setting_in_bytes('memory_limit')); ?></td>
	</tr>
	<tr>
		<td><b><?= elgg_echo('admin:server:label:mem_used'); ?></b></td>
		<td><?= number_format(memory_get_peak_usage()); ?></td>
	</tr>
	<tr>
		<td><b><?= elgg_echo('admin:server:label:post_max_size'); ?></b></td>
		<td><?= number_format($post_max_size); ?></td>
	</tr>
	<tr>
		<td><b><?= elgg_echo('admin:server:label:upload_max_filesize'); ?></b></td>
		<td><?= number_format($upload_max_filesize) . '&nbsp; ' . $post_max_size_warning; ?></td>
	</tr>
</table>
<?php
// Show phpinfo page
if (elgg_get_config('allow_phpinfo') !== true) {
	return;
}

echo elgg_format_element('div', ['class' => 'mts'], elgg_view('output/url', [
	'text' => elgg_echo('admin:server:label:phpinfo'),
	'href' => 'phpinfo',
	'target' => '_blank',
]));
