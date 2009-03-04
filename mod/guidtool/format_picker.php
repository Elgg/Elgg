<?php
	/**
	 * Elgg GUID Tool
	 * 
	 * @package ElggGUIDTool
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	global $CONFIG;
	
	admin_gatekeeper();
	set_context('admin');
	
	$formats = guidtool_get_import_actions();

	$title = elgg_echo("guidtool:pickformat");
	$body = elgg_view_title($title);
	$body .= elgg_view('forms/guidtool/format', array('formats' => $formats));
	
	$body = elgg_view_layout('two_column_left_sidebar', '', $body);
		
	page_draw($title, $body);
?>