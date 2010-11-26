<?php

	/**
	 * Elgg hoverover extender for messages
	 * 
	 * @package ElggMessages
	 */
	 
	 //need to be logged in to send a message
	 if (isloggedin()) {

?>

	<p class="user_menu_messages">
		<a href="<?php echo $vars['url']; ?>pg/messages/compose/?send_to=<?php echo $vars['entity']->guid; ?>"><?php echo elgg_echo("messages:sendmessage"); ?></a>
	</p>
	
<?php

	}

?>