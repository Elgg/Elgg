<?php
	/**
	 * View the widget
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	$group_guid = get_input('group_guid');
	$limit = get_input('limit', 8);
	$offset = 0;
	
	if ($vars['entity']->limit)
		$limit = $vars['entity']->limit;
		
	$group_guid = $vars['entity']->group_guid;

	if ($group_guid)
	{	
		$group = get_entity($group_guid);	
		$members = $group->getMembers($limit, $offset);
		$count = $group->getMembers($limit, $offset, true);
		
		$result = elgg_view_entity_list($members, $count, $offset, $limit, false, false, false);
	}
	else
	{
		$result = elgg_echo('groups:widgets:members:label:pleaseedit');
	}
	
	echo $result;
?>