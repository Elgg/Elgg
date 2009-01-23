<?php

	/**
	 * Elgg user display
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

		if ($vars['full']) {
			echo elgg_view("profile/userdetails",$vars);
		} else {
			if (get_input('search_viewtype') == "gallery") {
				echo elgg_view('profile/gallery',$vars); 				
			} else {
				echo elgg_view("profile/listing",$vars);
			}
		}
	
?>