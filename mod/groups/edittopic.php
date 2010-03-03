<?php

	/**
	 * Elgg Groups edit a forum topic page
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	// Load Elgg engine
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
		gatekeeper();
		
	get_input('group');
	$page_owner = set_page_owner((int)get_input('group'));
	
	// check the user is a member of the group
	//if (!$page_owner->isMember($_SESSION['user'])) forward();
	
	if (!(page_owner_entity() instanceof ElggGroup)) forward();
	
	//get the topic
	$topic = get_entity((int) get_input('topic'));
		
	// sort the display
	    $area2 = elgg_view("forms/forums/edittopic", array('entity' => $topic));
	    $body = elgg_view_layout('two_column_left_sidebar', '', $area2);
		
	// Display page
		page_draw(elgg_echo('groups:edittopic'),$body);
		
?>