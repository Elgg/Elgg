<?php
	/**
	 * Elgg log browser.
	 * 
	 * @package ElggLogBrowser
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	if (get_input('search_viewtype') == "gallery") {
		echo elgg_view('logbrowser/gallery',$vars); 				
	} else {
		echo elgg_view("logbrowser/listing",$vars);
	}
	
?>