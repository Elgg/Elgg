<?php

	/**
	 * Generic search viewer
	 * Given a GUID, this page will try and display any entity
	 * 
	 * @package Elgg
	 * @subpackage Core

	 * @author Curverider Ltd

	 * @link http://elgg.org/
	 */

	// Load Elgg engine
		require_once(dirname(dirname(__FILE__)) . "/engine/start.php");
		
	// Set context
		set_context('search');
		
	// Get input
		$tag = stripslashes(get_input('tag'));
		
		if (!empty($tag)) {
			$title = sprintf(elgg_echo('groups:searchtitle'),$tag);
			$body = "";
			$body .= elgg_view_title($title); // elgg_view_title(sprintf(elgg_echo('searchtitle'),$tag));
			$body .= elgg_view('group/search/startblurb',array('tag' => $tag));
			$body .= list_group_search($tag);
			//$body = elgg_view_layout('two_column_left_sidebar','',$body);
		} else {
			$title = elgg_echo('item:group');
			$body .= elgg_view_title($title);
			$body .= list_entities('groups');
		}
		
		$body = elgg_view_layout('two_column_left_sidebar','',$body);
		page_draw($title,$body);

?>