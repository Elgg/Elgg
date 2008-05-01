<?php
	/**
	 * Elgg file icons.
	 * Displays an icon, depending on its mime type, for a file. 
	 * Optionally you can specify a size.
	 * 
	 * @package ElggFile
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	
	$mime = $vars['mimetype'];
	
	$width = $vars['width'];
	$height = $vars['height'];
	
	if (!$width) $width = 100;
	if (!$height) $height = 100;
	
	echo $mime;
?>