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

$pages = elgg_list_entities(array('types' => 'object', 'subtypes' => 'page_top', 'container_guid' => elgg_get_page_owner_guid(), 'limit' => $num_display, 'full_view' => FALSE));

if ($pages) {
	$pagesurl = elgg_get_site_url() . "pg/pages/owned/" . elgg_get_page_owner()->username;
	$pages .= "<div class=\"pages_widget_singleitem_more\"><a href=\"{$pagesurl}\">" . elgg_echo('pages:more') . "</a></div>";
}

echo "<div id=\"pages_widget\">" . $pages . "</div>";