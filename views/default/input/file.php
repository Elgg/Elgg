<?php
/**
 * Elgg file input
 * Displays a file input field
 *
 * @uses $vars['value']    The current value if any
 * @uses $vars['class']    Additional CSS class
 * @uses $vars['max_size'] bool|int true for autodetection of max upload size (default), false to disable or int for a custom file size to check
 */

if (!empty($vars['value'])) {
	echo elgg_format_element('div', [
		'class' => 'elgg-state elgg-state-warning',
	], elgg_echo('fileexists'));
	
	unset($vars['value']); // the value attribute isn't supported for file inputs
}

$vars['class'] = elgg_extract_class($vars, 'elgg-input-file');

$defaults = [
	'disabled' => false,
	'type' => 'file'
];

$vars = array_merge($defaults, $vars);

$max_size = elgg_extract('max_size', $vars, true);
unset($vars['max_size']);

if ($max_size !== false) {
	if ($max_size === true) {
		$post_max_size = elgg_get_ini_setting_in_bytes('post_max_size');
		$upload_max_filesize = elgg_get_ini_setting_in_bytes('upload_max_filesize');
		
		$max_size = min($upload_max_filesize, $post_max_size);
	}
	
	$max_size = (int) $max_size;
	
	$vars['data-max-size'] = $max_size;
	$vars['data-max-size-message'] = elgg_echo('upload:error:ini_size') . ' ' . elgg_echo('input:file:upload_limit', [elgg_format_bytes($max_size)]);
	
	elgg_require_js('input/file');
}

echo elgg_format_element('input', $vars);
