<?php

    /**
	 * Elgg delete a message action page
	 * It is worth noting that due to the nature of a messaging system and the fact 2 people access
	 * the same message, messages don't actually delete, they are just removed from view for the user who deletes
	 * 
	 * @package ElggMessages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */
	 
	// Need to be logged in to do this
	    gatekeeper();
 
    // grab details sent from the form
        $message_id_array = get_input('message_id');
        if (!is_array($message_id_array)) $message_id_array = array($message_id_array);
        $type = get_input('type'); // sent message or inbox
        $success = true;
        $submit = get_input('submit');
        $offset = get_input('offset');
        
        foreach($message_id_array as $message_id) {
        
	    // get the message object
	        $message = get_entity($message_id);
	        
	    // Make sure we actually have permission to edit and that the object is of sub-type messages
			if ($message && $message->getSubtype() == "messages") {
	    		
				if ($submit == elgg_echo('delete')) {
					if ($message->delete()) {
					} else {
						$success = false;
					}
				} else {
					if ($message->readYet = 1) {
					} else {
						$success = false;
					}
				}
	            
	        }else{
	            
	            // display the error message
	            $success = false;
				
			}
		
        }
        
        if ($success) {
        	if ($submit == elgg_echo('delete')) {
        		system_message(elgg_echo("messages:deleted"));
        	} else {
        		system_message(elgg_echo("messages:markedread"));
        	}
			// check to see if it is a sent message to be deleted
		    if($type == 'sent'){
			    forward("mod/messages/sent.php?offset={$offset}");
		    }else{
    		    forward("mod/messages/?username=" . $_SESSION['user']->username . "&offset={$offset}");
		    }
        } else {
        	register_error(elgg_echo("messages:notfound"));
        	forward($_SERVER['HTTP_REFERER']);
        }
                 
    
?>