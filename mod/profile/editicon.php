<?php

	/**
	 * Elgg upload new profile icon
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ben Werdmuller <ben@curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	// Load the Elgg framework
		require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
		
	// Make sure we're logged in
		if (!isloggedin()) forward();
		
	// Get the form and correct canvas area
		$body = elgg_view_layout("one_column", elgg_view("profile/editicon"));
		
	// Draw the page
		page_draw(elgg_echo("profile:editicon"),$body);

?>