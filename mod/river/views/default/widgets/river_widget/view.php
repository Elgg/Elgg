<?php
	/**
	 * View the widget
	 * 
	 * @package ElggRiver
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	$owner = page_owner_entity();
	$limit = 8;
	
	if ($vars['entity']->limit)
		$limit = $vars['entity']->limit;
	echo elgg_view_river($owner->guid, $limit);
?>