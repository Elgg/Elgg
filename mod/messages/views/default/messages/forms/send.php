<?php
/**
* Elgg send a message view
* 
* @package ElggMessages
 * @uses $vars['friends'] This is an array of a user's friends and is used to populate the list of
 * people the user can message
 *
 */
 
//grab the user id to send a message to. This will only happen if a user clicks on the 'send a message'
//link on a user's profile or hover-over menu
$send_to = get_input('send_to');
if ($send_to === "")
	$send_to = $_SESSION['msg_to'];

$msg_title = $_SESSION['msg_title'];
$msg_content = $_SESSION['msg_contents'];

// clear sticky form cache in case user browses away from page and comes back 
unset($_SESSION['msg_to']);
unset($_SESSION['msg_title']);
unset($_SESSION['msg_contents']);
?>
<form id="messages_send_form" action="<?php echo $vars['url']; ?>action/messages/send" method="post" name="messageForm">
<?php
	echo elgg_view('input/securitytoken'); 
        //check to see if the message recipient has already been selected
		if($send_to){
			
			//get the user object  
	        $user = get_user($send_to);
	        
	        echo "<div class='entity_listing messages clearfloat'><div class='entity_listing_icon'>".elgg_view("profile/icon",array('entity' => $user, 'size' => 'tiny'))."</div>";
	        
	        //draw it
			echo "<div class='entity_listing_info'>".elgg_echo("messages:to").": <a href='{$vars['url']}pg/profile/".$user->username."'>".$user->name."</a>";
			//set the hidden input field to the recipients guid
	        echo "<input type='hidden' name='send_to' value=\"{$send_to}\" />";	
			echo "</div></div>";
		    
        } else {
    ?>
        
        <p class="margin_top"><label><?php echo elgg_echo("messages:to"); ?>: </label>
	    <select name='send_to'>
	    <?php 
			// make the first option blank
	    	echo "<option value=''>".elgg_echo("messages:recipient")."</option>";
	        foreach($vars['friends'] as $friend){
    	        //populate the send to box with a user's friends
			    echo "<option value='{$friend->guid}'>" . $friend->name . "</option>";
		    }
        ?>
		</select></p>
    <?php
        }
    ?>
    
	<p class="margin_top"><label><?php echo elgg_echo("messages:title"); ?>: <br /><input type='text' name='title' value='<?php echo $msg_title; ?>' class="input_text" /></label></p>
	<p class="longtext_inputarea"><label><?php echo elgg_echo("messages:message"); ?>:</label>
	<?php
		echo elgg_view("input/longtext", array(
						"internalname" => "message",
						"value" => $msg_content,
		));
	?>
	</p>
	<p><input type="submit" class="submit_button" value="<?php echo elgg_echo("messages:fly"); ?>" /></p>
</form>
