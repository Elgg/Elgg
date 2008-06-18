<?php

	/**
	 * Generic search viewer
	 * Given a GUID, this page will try and display any entity
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	// Load Elgg engine
		require_once(dirname(dirname(__FILE__)) . "/engine/start.php");
		
	// Get input
		$tag = get_input('tag');
		$subtype = get_input('subtype');
		$objecttype = get_input('object');
		
		$body = list_entities_from_metadata("", $tag, "", $subtype);
		$body = elgg_view_layout('one_column',$body);
		
		page_draw(sprintf(elgg_echo('searchtitle'),$tag),$body);

?>