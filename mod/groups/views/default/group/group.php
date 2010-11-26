<?php 
	/**
	 * Elgg groups profile display
	 * 
	 * @package ElggGroups
	 */

	if ($vars['full']) {
		echo elgg_view("groups/groupprofile",$vars);
	} else {
		if (get_input('search_viewtype') == "gallery") {
			echo elgg_view('groups/groupgallery',$vars); 				
		} else {
			echo elgg_view("groups/grouplisting",$vars);
		}
	}
?>