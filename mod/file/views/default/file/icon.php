<?php
	/**
	 * Elgg file icons.
	 * Displays an icon, depending on its mime type, for a file. 
	 * Optionally you can specify a size.
	 * 
	 * @package ElggFile
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	global $CONFIG;
	
	$mime = $vars['mimetype'];
	
	// is this request for an image thumbnail
	if (isset($vars['thumbnail'])) {
		$thumbnail = $vars['thumbnail'];
	} else {
		$thumbnail = false;
	}

	// default size is small for thumbnails
	if (isset($vars['size'])) {
		$size = $vars['size'];
	} else {
		$size = 'small';
	}
	
	// Handle 
	switch ($mime)
	{
		case 'image/jpg' 	:
		case 'image/jpeg' 	:
		case 'image/pjpeg' 	:
		case 'image/png' 	:
		case 'image/x-png'	:
		case 'image/gif' 	:
		case 'image/bmp' 	: 
			if ($thumbnail) {
				echo "<img src=\"{$vars['url']}mod/file/thumbnail.php?file_guid={$vars['file_guid']}&size={$size}\" border=\"0\" />";				
			} else {
				if (!empty($mime) && elgg_view_exists("file/icon/{$mime}")) {
					echo elgg_view("file/icon/{$mime}", $vars);
				} else if (!empty($mime) && elgg_view_exists("file/icon/" . substr($mime,0,strpos($mime,'/')) . "/default")) {
					echo elgg_view("file/icon/" . substr($mime,0,strpos($mime,'/')) . "/default", $vars);
				} else {
					echo "<img src=\"". elgg_view('file/icon/default',$vars) ."\" border=\"0\" />";
				}	
			}
			
		break;
		default :
			if (!empty($mime) && elgg_view_exists("file/icon/{$mime}")) {
				echo elgg_view("file/icon/{$mime}", $vars);
			} else if (!empty($mime) && elgg_view_exists("file/icon/" . substr($mime,0,strpos($mime,'/')) . "/default")) {
				echo elgg_view("file/icon/" . substr($mime,0,strpos($mime,'/')) . "/default", $vars);
			} else {
				echo "<img src=\"". elgg_view('file/icon/default',$vars) ."\" border=\"0\" />";
			} 
		break;
	}

?>