<?php
	/**
	 * Elgg activity plugin.
	 * 
	 * @package ElggActivity
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	require_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");
	
	$limit = get_input('limit', 20);
	$offset = get_input('offset');
	$type = get_input('type');
	$subtype = get_input('subtype');
	$title = elgg_view_title(elgg_echo('activity:your'));
	
	if (page_owner())
	{
		global $autofeed;
		$autofeed = true;
		
		if (elgg_get_viewtype()=='opendd')
			$body = elgg_view('activity/dashboard', array('activity' => activity_get_activity_opendd($limit, $offset, $type, $subtype, page_owner())));
		else
			$body = elgg_view('activity/dashboard', array('activity' => activity_get_activity($limit, $offset, $type, $subtype, page_owner())));
	}
	else
		$body = elgg_echo('activity:usernotfound');
	
	page_draw(elgg_echo('activity:your'),elgg_view_layout("two_column_left_sidebar", '', $title . $body));

?>