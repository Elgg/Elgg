<?php
	/**
	 * Elgg relationship create event for groups
	 * Display something in the river when a group is joined
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	$performed_by = $vars['performed_by'];
	$log_entry = $vars['log_entry'];
	$object = $vars['entity'];
	
	// Find out what type of relationship we're dealing with (will only display a few)
	if ($object instanceof ElggRelationship)
	{
		
		switch ($object->relationship)
		{
			// Friending
			case 'member' :
				
				$user = get_entity($object->guid_one);
				$group = get_entity($object->guid_two);
				
				if (($user instanceof ElggUser) && ($group instanceof ElggGroup))
				{
					echo "<a href=\"{$user->getURL()}\">{$user->name}</a> ";
					echo elgg_echo("groups:river:member");
					echo " '<a href=\"{$group->getURL()}\">{$group->title}</a>'";
				}
				
			break;
		}
	}
		
?>