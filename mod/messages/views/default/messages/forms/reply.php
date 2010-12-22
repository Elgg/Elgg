<?php

    /**
	 * Elgg reply to a message form
	 * @uses $vars['entity'] This is the message being replied to
	 */
	
	// fix for RE: RE: RE: that builds on replies
	$reply_title = $vars['entity']->title;
	if (strncmp($reply_title, "RE:", 3) != 0) {
		$reply_title = "RE: " . $reply_title;
	}
?>

<form class="margin-top" id="messages_reply_form" action="<?php echo elgg_get_site_url(); ?>action/messages/send" method="post" name="messageForm">
	<?php echo elgg_view('action/securitytoken'); ?>

    <!-- populate the title space with the orginal message title, inserting re: before it -->						        
	<p><label><?php echo elgg_echo("messages:title"); ?>: <br /><input type='text' name='title' class="input-text" value='<?php echo $reply_title; ?>' /></label></p>
	<p><label><?php echo elgg_echo("messages:message"); ?>: <br /><textarea name='message' value='' class="input-textarea" /></textarea></label></p>
		
	<p>
	    <?php
                
	        //pass across the guid of the message being replied to
    	    echo "<input type='hidden' name='reply' value='" . $vars['entity']->getGUID() . "' />";
    	    //pass along the owner of the message being replied to
    	    echo "<input type='hidden' name='send_to' value='BAAA" . $vars['entity']->fromId . "' />";

			echo elgg_view('input/submit', array('value' => elgg_echo("messages:fly")));
	    ?>
	</p>
	
</form>
	
	<?php
        //display the message you are replying to
		if (isset($vars['entity'])) {
    		
    		echo "<h3>" . elgg_echo("messages:replying") . "</h3>";
    		echo $vars['entity']->description;
    		
		}
    ?>
