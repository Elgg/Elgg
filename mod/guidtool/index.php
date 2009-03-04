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

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	admin_gatekeeper();
	set_context('admin');
	
	$title = elgg_echo("guidtool");
	$body = elgg_view_title($title);
	
	$context = get_context(); 
	set_context('search');
	
	$limit = get_input('limit', 10);
	$offset = get_input('offset');	
	
	// Get entities
	$entities = get_entities("","","","",$limit, $offset);
	$count = get_entities("","","","",$limit, $offset, true);
	
	$wrapped_entries = array();
	
	foreach ($entities as $e)
	{
		$tmp = new ElggObject();
		$tmp->subtype = 'guidtoolwrapper';
		$tmp->entity = $e;
		$wrapped_entries[] = $tmp;
	}
	
	$body .= elgg_view_entity_list($wrapped_entries, $count, $offset, $limit, false);
	
	set_context($context);
	
// Display main admin menu
	page_draw($title,elgg_view_layout("two_column_left_sidebar", '', $body));
?>