<?php
	/**
	 * Elgg Pages
	 * 
	 * @package ElggPages
	 */

	if ($vars['full']) {
		echo elgg_view("pages/pageprofile",$vars);
	} else {
		if (get_input('search_viewtype') == "gallery") {
			echo elgg_view('pages/pagegallery',$vars); 				
		} else {
			echo elgg_view("pages/pagelisting",$vars);
		}
	}
?>