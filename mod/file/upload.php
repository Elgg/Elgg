<?php
	/**
	 * Elgg file browser uploader
	 * 
	 * @package ElggFile
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	gatekeeper();

	// Render the file upload page
	page_draw(elgg_echo("file:upload"), elgg_view("file/upload", NULL));
?>