<?php

    /**
	 * Elgg message board widget ajax logic page
	 *
	 * @package ElggMessageBoard
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010 - 2009
	 * @link http://elgg.com/
	 */

    // Load Elgg engine will not include plugins
     require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");
    
    //get the required info
    
    //the actual message
    $message = get_input('messageboard_content');
    //the number of messages to display
    $numToDisplay = get_input('numToDisplay');    
    //get the full page owner entity
    $user = get_entity($_POST['pageOwner']);
    
    //stage one - if a message was posted, add it as an annotation    
    if($message){
        
       // If posting the comment was successful, send message
	   	if ($user->annotate('messageboard',$message,$user->access_id, $_SESSION['user']->getGUID())) {
					
			global $CONFIG;
					
			if ($user->getGUID() != $_SESSION['user']->getGUID())
				notify_user($user->getGUID(), $_SESSION['user']->getGUID(), elgg_echo('messageboard:email:subject'), 
				sprintf(
					elgg_echo('messageboard:email:body'),
					$_SESSION['user']->name,
					$message,
					$CONFIG->wwwroot . "pg/messageboard/" . $user->username,
					$_SESSION['user']->name,
					$_SESSION['user']->getURL()
					)
				); 
       		
			// add to river
	    	add_to_river('river/object/messageboard/create','messageboard',$_SESSION['user']->guid,$user->guid);
   		}else{
	   		register_error(elgg_echo("messageboard:failure"));
		}
        
    } else {
        
        echo elgg_echo('messageboard:somethingwentwrong');
        
    }
    
    //step two - grab the latest messageboard contents, this will include the message above, unless an issue 
    //has occurred.
    $contents = $user->getAnnotations('messageboard', $numToDisplay, 0, 'desc'); 
    
    //step three - display the latest results
    if($contents){
        
        foreach($contents as $content) {
				
			echo elgg_view("messageboard/messageboard_content", array('annotation' => $content));
				
		}
        
    }

    
?>