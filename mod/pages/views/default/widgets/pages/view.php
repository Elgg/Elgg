<style type="text/css">
#pages_widget .pagination {
    display:none;
}
</style>
<?php

     /**
	 * Elgg pages widget edit
	 *
	 * @package ElggPages
	 */
     
     $num_display = (int) $vars['entity']->pages_num;
	 if (!$num_display) {
		 $num_display = 4;
	 }
     
     $pages = elgg_list_entities(array('types' => 'object', 'subtypes' => 'page_top', 'container_guid' => page_owner(), 'limit' => $num_display, 'full_view' => FALSE));
	 
	 if ($pages) {
		$pagesurl = $vars['url'] . "pg/pages/owned/" . page_owner_entity()->username;
		$pages .= "<div class=\"widget_more_wrapper\"><a href=\"{$pagesurl}\">" . elgg_echo('pages:more') . "</a></div>";
	 }

     echo "<div id=\"pages_widget\">" . $pages . "</div>";
     
?>