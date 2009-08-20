<?php

	/**
	 * Elgg user display
	 * 
	 * @package Elgg
	 * @subpackage Core


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