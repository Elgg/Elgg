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
	
	// Get the current page's owner
		if ($container = $pages->container_guid) {
			set_page_owner($container);
		}
		$page_owner = page_owner_entity();
		if ($page_owner === false || is_null($page_owner)) {
			$page_owner = $_SESSION['user'];
			set_page_owner($page_owner->getGUID());
		}
	
	$title = elgg_echo("pages:edit");
	$body = elgg_view_title($title);
	
	if (($pages) && ($pages->canEdit()))
	{
		$body .= elgg_view("forms/pages/edit", array('entity' => $pages));
			 
	} else {
		$body .= elgg_echo("pages:noaccess");
	}
	
	$body = elgg_view_layout('two_column_left_sidebar', '', $body);
	
	page_draw($title, $body);
?>