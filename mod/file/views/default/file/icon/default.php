<?php
/**
 * Default file icon
 */

$type = elgg_get_array_value('type', $vars, 'general');

$size = elgg_get_array_value('size', $vars, '');
if ($size == 'large') {
	$ext = '_lrg';
} else {
	$ext = '';
}

$src = elgg_get_site_url() . "mod/file/graphics/icons/{$type}{$ext}.gif";
echo "<img src=\"$src\" />";
