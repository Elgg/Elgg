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

	$page_guid = get_input('page_guid');
	set_context('pages');

	if (is_callable('group_gatekeeper')) group_gatekeeper();

	$pages = get_entity($page_guid);
	if (!$pages) forward();

	$container = $pages->container_guid;

	if ($container) {
		set_page_owner($container);
	} else {
		set_page_owner($pages->owner_guid);
	}

	global $CONFIG;
	// add_submenu_item(sprintf(elgg_echo("pages:user"), page_owner_entity()->name), $CONFIG->url . "pg/pages/owned/" . page_owner_entity()->username, 'pageslinksgeneral');

	if ($pages->canEdit()) {
		add_submenu_item(elgg_echo('pages:newchild'),"{$CONFIG->wwwroot}pg/pages/new/?parent_guid={$pages->getGUID()}&container_guid=" . page_owner(), 'pagesactions');
		$delete_url = elgg_add_action_tokens_to_url("{$CONFIG->wwwroot}action/pages/delete?page={$pages->getGUID()}");
		add_submenu_item(elgg_echo('pages:delete'), $delete_url, 'pagesactions', true);
	}

	//if the page has a parent, get it
	if($parent_page = get_entity(get_input("page_guid")))
		$parent = $parent_page;

	$title = $pages->title;

	// Breadcrumbs
	$body = elgg_view('pages/breadcrumbs', array('page_owner' => page_owner_entity(), 'parent' => $parent));

	$body .= elgg_view_title($pages->title);
	$body .= elgg_view_entity($pages, true);

	//add comments
	$body .= elgg_view_comments($pages);

	pages_set_navigation_parent($pages);
	$sidebar = elgg_view('pages/sidebar/tree');

	$body = elgg_view_layout('two_column_left_sidebar', '', $body, $sidebar);

	// Finally draw the page
	page_draw($title, $body);

?>
