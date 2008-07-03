<?php
	/**
	 * Elgg groups plugin
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	gatekeeper();

	// Render the file upload page
	
	$body = elgg_view_layout('one_column', elgg_view("forms/groups/edit"));
	
	page_draw(elgg_echo("groups:new"), $body);
?>