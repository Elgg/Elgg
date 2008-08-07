<?php
	/**
	 * Elgg log browser.
	 * 
	 * @package ElggLogBrowser
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	admin_gatekeeper();
	set_context('admin');
	
	$limit = get_input('limit', 40);
	$offset = get_input('offset');
	
	$user_guid = get_input('user_guid',0);
	if ($user_guid) {
		$user = (int) $user_guid;
	} else {
		$user = "";
	}
	
	$title = elgg_view_title(elgg_echo('logbrowser'));
	
	// Get log entries
	$log = get_system_log($user, "", "", $limit, $offset);
	$count = get_system_log($user, "", "", $limit, $offset, true);
	$log_entries = array();
	
	foreach ($log as $l)
	{
		$tmp = new ElggObject();
		$tmp->subtype = 'logwrapper';
		$tmp->entry = $l;
		$log_entries[] = $tmp;
	}
	set_context('search');
	$result = elgg_view_entity_list($log_entries, $count, $offset, $limit, false, false);
	set_context('admin');
		
// Display main admin menu
	page_draw(elgg_echo('logbrowser'),elgg_view_layout("two_column_left_sidebar", '', $title.$result));

?>