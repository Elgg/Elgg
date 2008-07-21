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
	$title = elgg_echo("groups:new");
	$body = elgg_view_title($title);
	$bodt .= elgg_view("forms/groups/edit");
	
	$body = elgg_view_layout('one_column', $body);
	
	page_draw($title, $body);
?>