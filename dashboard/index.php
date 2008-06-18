<?php

	/**
	 * Elgg dashboard
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	// Get the Elgg engine
		require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

	// Ensure that only logged-in users can see this page
		gatekeeper();
		
	// Set context and title
		set_context('dashboard');
		set_page_owner($_SESSION['user']->getGUID());
		$title = elgg_echo('dashboard');
		
	// Try and get the user from the username and set the page body accordingly
		$body = elgg_view_layout('widgets',"");
		
		page_draw($title, $body);
		
?>