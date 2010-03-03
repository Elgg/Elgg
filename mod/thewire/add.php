<?php

	/**
	 * Elgg thewire add entry page
	 * 
	 * @package ElggTheWire
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 *
	 */

	// Load Elgg engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
	// If we're not logged in, forward to the front page
		if (!isloggedin()) forward(); 
		
	// choose the required canvas layout and items to display
	    $area2 = elgg_view_title(elgg_echo('thewire:add'));
	    $area2 .= elgg_view("thewire/forms/add");
	    $body = elgg_view_layout("two_column_left_sidebar", '',$area2);
		
	// Display page
		page_draw(elgg_echo('thewire:addpost'),$body);
		
?>