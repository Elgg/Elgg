<?php
	/**
	 * Elgg relationship create event.
	 * Display something in the river when a relationship is created.
	 * 
	 * @package Elgg
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	$performed_by = $vars['performed_by'];
	$log_entry = $vars['log_entry'];
	$object = $vars['object'];
	
	// Find out what type of relationship we're dealing with (will only display a few)
	if ($object instanceof ElggRelationship)
	{
		switch ($object->relationship)
		{
			// Friending
			case 'friend' :
			case 'friends' : // 'friends' shouldn't be used, but just incase :)
				
				// Get second object
				$userb = get_entity($object->guid_two);
				
				// Only users can be friends
				if (($performed_by instanceof ElggUser) && ($userb instanceof ElggUser))
				{
					// User A
					echo "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a> ";
				
					// Verb
					echo elgg_echo('river:relationship:friend');
				
					// user B
					echo " <a href=\"{$userb->getURL()}\">{$userb->name}</a>";
				}
				
			break;
		}
	}
		
?>