<?php

/**
 * Elgg message board widget ajax logic page
 *
 * @package ElggMessageBoard
 */

// Load Elgg engine will not include plugins
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . "/engine/start.php");

//get the required info

//the actual message
$message = get_input('messageboard_content');
//the number of messages to display
$numToDisplay = get_input('numToDisplay');    
//get the full page owner entity
$user = get_entity(get_input('pageOwner'));

//stage one - if a message was posted, add it as an annotation    
if ($message) {

	if (!messageboard_add(get_loggedin_user(), $user, $message, $user->access_id)) {
		echo elgg_echo("messageboard:failure");
	}

} else {
	echo elgg_echo('messageboard:somethingwentwrong');
}


//step two - grab the latest messageboard contents, this will include the message above, unless an issue 
//has occurred.
$contents = $user->getAnnotations('messageboard', $numToDisplay, 0, 'desc'); 

//step three - display the latest results
if ($contents) {
	foreach ($contents as $content) {
		echo elgg_view("messageboard/messageboard_content", array('annotation' => $content));
	}
}
