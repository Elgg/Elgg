<?php

	/**
	 * Elgg messages topbar extender
	 * 
	 * @package ElggMessages
	 */
	 
	 //need to be logged in to send a message
	 gatekeeper();

	//get unread messages
	$num_messages = count_unread_messages();
	if($num_messages){
		$num = $num_messages;
	} else {
		$num = 0;
	}

	if($num == 0){

?>

<a href="<?php echo $vars['url']; ?>pg/messages/inbox/<?php echo get_loggedin_user()->username; ?>" class="privatemessages" >&nbsp;</a>
	
<?php
    }else{
?>

<a href="<?php echo $vars['url']; ?>pg/messages/inbox/<?php echo get_loggedin_user()->username; ?>" class="privatemessages_new" >[<?php echo $num; ?>]</a>
	
<?php
    }
?>