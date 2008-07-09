<?php
	/**
	 * Elgg OpenDD aggregator
	 * 
	 * @package ElggOpenDD
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	if ($vars['full']) {
		echo elgg_view("opendd/profile",$vars);
	} else {
		if (get_input('search_viewtype') == "gallery") {
			echo elgg_view('opendd/gallery',$vars); 				
		} else {
			echo elgg_view("opendd/listing",$vars);
		}
	}
?>