<?php

	/**
	 * Elgg thewire index page
	 * 
	 * @package Elggthewire
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	// Load Elgg engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
	// Get the current page's owner
		$page_owner = page_owner_entity();
		if ($page_owner === false || is_null($page_owner)) {
			$page_owner = $_SESSION['user'];
			set_page_owner($page_owner->getGUID());
		}
		
	// title
	    $area2 = elgg_view_title(elgg_echo("thewire:read"));
	    
	//add form
		$area2 .= elgg_view("thewire/forms/add");
	    
	// Display the user's wire
		$area2 .= list_user_objects($page_owner->getGUID(),'thewire'); // elgg_view("thewire/view",array('entity' => $thewire));
    
    //select the correct canvas area
	    $body = elgg_view_layout("two_column_left_sidebar", '', $area2);
		
	// Display page
		page_draw(sprintf(elgg_echo('thewire:user'),$page_owner->name),$body);
		
?>