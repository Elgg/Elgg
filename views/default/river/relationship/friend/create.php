<?php
	/**
	 * Elgg relationship create event.
	 * Display something in the river when a relationship is created.
	 * 
	 * @package Elgg
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	$statement = $vars['statement'];
	
	$performed_by = $statement->getSubject();
	$event = $statement->getEvent();
	$object = $statement->getObject();
	
	if (is_array($object))
	{
		switch ($object['relationship'])
		{
			// Friending
			case 'friend' :
			case 'friends' : // 'friends' shouldn't be used, but just incase :)
				
				// Get second object
				$userb = $object['object'];
				
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