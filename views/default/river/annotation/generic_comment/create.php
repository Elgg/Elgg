<?php
	/**
	 * Elgg generic comment create event.
	 * Display something in the river when a comment is left.
	 * 
	 * @package Elgg
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	$statement = $vars['statement'];
	
	$performed_by = $statement->getSubject();
	$event = $statement->getEvent();
	$object = $statement->getObject();
	
	if (is_array($object))
	{
		
		switch ($object['subject']->name)
		{
			// Generic comments
			case 'generic_comment' :
				
				// Get the item that's been commented on
				$item = $object['object'];
				
				// Make sure the comment was left by a user ...
				if (($performed_by instanceof ElggUser) && ($item instanceof ElggEntity))
				{
					
					$type = $item->getType();
					$subtype = $item->getSubtype();
					
					// Generate a string of them form riveritem:single:type:subtype for our description
					$desc = 'riveritem:single:' . $type; if (!empty($subtype)) $desc .= ':' . $subtype;

					// The person leaving the comment
					$sentence_performed_by =  "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a> ";
					
					// The item with the ocmment left on it
					$sentence_object = " <a href=\"{$item->getURL()}\">". elgg_echo($desc) ."</a>";
					
					echo sprintf(elgg_echo('riveraction:annotation:generic_comment'),$sentence_performed_by, $sentence_object);
				}
				
			break;
		}
	}
		
?>