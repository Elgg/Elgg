<?php
/**
 * Elgg file icons.
 * Displays an icon, depending on its mime type, for a file. 
 * Optionally you can specify a size.
 * 
 * @package ElggFile
 *
 * @uses $vars['size']
 * @uses $vars['mimetype']
 * @uses $vars['thumbnail']
 * @uses $vars['file_guid']
 */

$mime = $vars['mimetype'];
$simple_type = get_general_file_type($mime);

// is this request for an image thumbnail
$thumbnail = elgg_get_array_value('thumbnail', $vars, false);

// default size is small for thumbnails
$size = elgg_get_array_value('size', $vars, 'small');

if ($simple_type == 'image' && $thumbnail) {
	$icon = "<img src=\"" . elgg_get_site_url() . "mod/file/thumbnail.php?file_guid={$vars['file_guid']}&size={$size}\" />";
} else {
	$base_type = substr($mime, 0, strpos($mime, '/'));
	if ($mime && elgg_view_exists("file/icon/$mime")) {
		$icon = elgg_view("file/icon/{$mime}", $vars);
	} else if ($mime && elgg_view_exists("file/icon/$base_type/default")) {
		$icon = elgg_view("file/icon/$base_type/default", $vars);
	} else {
		$icon = elgg_view('file/icon/default', $vars);
	}
}

echo $icon;
