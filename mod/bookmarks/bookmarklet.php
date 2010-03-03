<?php

	/**
	 * Elgg bookmarks plugin bookmarklet page
	 * 
	 * @package ElggBookmarks
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.org/
	 */

	// Start engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

		gatekeeper();
		
    // Get the current page's owner
		$page_owner = page_owner_entity();
		if ($page_owner === false || is_null($page_owner) && ($_SESSION['user'])) {
			$page_owner = $_SESSION['user'];
			set_page_owner($page_owner->getGUID());
		}
		
	// List bookmarks
		$area2 = elgg_view_title(elgg_echo('bookmarks:bookmarklet'));
		$area2 .= elgg_view('bookmarks/bookmarklet', array('pg_owner' => $page_owner));
		
	// Format page
		$body = elgg_view_layout('two_column_left_sidebar', $area1, $area2);
		
	// Draw it
		page_draw(elgg_echo('bookmarks:bookmarklet'),$body);

?>