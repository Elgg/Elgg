<?php
	/**
	 * Elgg GUID Tool
	 * 
	 * @package ElggGUIDTool
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	admin_gatekeeper();
	
	$context = get_context();
	set_context('search');
	
	$limit = get_input('limit', 10);
	$offset = get_input('offset');
	
	
	
// Display main admin menu
	page_draw(elgg_echo("guidtool"),elgg_view_layout("one_column", $body));
	set_context($context);
?>