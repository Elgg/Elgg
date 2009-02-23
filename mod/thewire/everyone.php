<?php

	/**
	 * Elgg view all thewire posts from all users page
	 * 
	 * @package ElggTheWire
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	// Load Elgg engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
		$area2 = elgg_view_title(elgg_echo("thewire:everyone"));
		
		//add form
		$area2 .= elgg_view("thewire/forms/add");

		$area2 .= list_entities('object','thewire'); // elgg_view("thewire/view",array('entity' => $thewireposts));
	    $body = elgg_view_layout("two_column_left_sidebar", '', $area2);
		
	// Display page
		page_draw(elgg_echo('thewire:everyone'),$body);
		
?>