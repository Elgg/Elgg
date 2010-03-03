<?php
	/**
	 * Elgg Pages
	 * 
	 * @package ElggPages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	gatekeeper();
		
	$page_guid = get_input('page_guid');
		
    $pages = get_entity($page_guid);
	if ($pages->container_guid) {
		set_page_owner($pages->container_guid);
	} else {
		set_page_owner($pages->owner_guid);
	}
	
	if (is_callable('group_gatekeeper')) group_gatekeeper();

	$limit = (int)get_input('limit', 20);
	$offset = (int)get_input('offset');
	
	$page_guid = get_input('page_guid');
	$pages = get_entity($page_guid);
	
	add_submenu_item(sprintf(elgg_echo("pages:user"), page_owner_entity()->name), $CONFIG->url . "pg/pages/owned/" . page_owner_entity()->username, 'pageslinksgeneral');
					 
	$title = $pages->title . ": " . elgg_echo("pages:history");
	$area2 = elgg_view_title($title);
	
	$context = get_context();
	
	set_context('search');
	
	$area2 .= list_annotations($page_guid, 'page', $limit, false);
	
	set_context($context);
	
	
	pages_set_navigation_parent($pages);
	$area3 = elgg_view('pages/sidebar/tree');
	
	$body = elgg_view_layout('two_column_left_sidebar', '', $area2, $area3);
	
	page_draw($title, $body);
?>