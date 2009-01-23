<?php

	/**
	 * Generic search viewer
	 * Given a GUID, this page will try and display any entity
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

	// Load Elgg engine
		require_once(dirname(dirname(__FILE__)) . "/engine/start.php");
		
	// Set context
		set_context('search');
		
	// Get input
		$tag = get_input('tag');
		
		if (!empty($tag)) {
			$title = sprintf(elgg_echo('users:searchtitle'),$tag);
			$body = "";
			$body .= elgg_view_title($title); // elgg_view_title(sprintf(elgg_echo('searchtitle'),$tag));
			$body .= elgg_view('user/search/startblurb',array('tag' => $tag));
			$body .= list_user_search($tag);
			//$body = elgg_view_layout('two_column_left_sidebar','',$body);
		} else {
			$title = elgg_echo('item:user');
			$body .= elgg_view_title($title);
			$body .= list_entities('user');
		}
		
		$body = elgg_view_layout('two_column_left_sidebar','',$body);
		page_draw($title,$body);

?>