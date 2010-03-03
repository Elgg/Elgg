<?php

    /**
	 * Elgg send a message page
	 *
	 * 
	 * @package ElggMessages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 *
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
	<div class="contentWrapper">
	<form action="<?php echo $vars['url']; ?>action/messages/send" method="post" name="messageForm">
			
	    <?php
		// security tokens.
		echo elgg_view('input/securitytoken');
			    
	        //check to see if the message recipient has already been selected
			if($send_to){
    			
    			//get the user object  
    	        $user = get_user($send_to);
    	        
    	        //draw it
    			echo "<label>" . elgg_echo("messages:to") . ":</label><div class=\"messages_single_icon\">" . elgg_view("profile/icon",array('entity' => $user, 'size' => 'tiny')) . $user->username;
    			echo "</div><br class=\"clearfloat\" />";
    			//set the hidden input field to the recipients guid
    	        echo "<input type=\"hidden\" name=\"send_to\" value=\"{$send_to}\" />";
    	        
    			    
	        }else{
    	            
        ?>
    	   		
            <p><label><?php echo elgg_echo("messages:to"); ?>: </label>
    	    <select name='send_to'>
    	    <?php 
    			//make the first option blank
    	    	echo "<option value=''></option>";
    	        foreach($vars['friends'] as $friend){
        			   
        	        //populate the send to box with a user's friends
    			    echo "<option value='{$friend->guid}'>" . $friend->name . "</option>";
    			        
    		    }
    		        
            ?>
    		</select></p>
    		    
        <?php
    		
	        }//end send_to if statement
		        
	    ?>
	    
		<p><label><?php echo elgg_echo("messages:title"); ?>: <br /><input type='text' name='title' value='<?php echo $msg_title; ?>' class="input-text" /></label></p>
		<p class="longtext_editarea"><label><?php echo elgg_echo("messages:message"); ?>: <br />
		<?php

				    echo elgg_view("input/longtext", array(
									"internalname" => "message",
									"value" => $msg_content,
													));
			
		?>
		</label></p>
		<p><input type="submit" class="submit_button" value="<?php echo elgg_echo("messages:fly"); ?>" /></p>
	
	</form>
	</div>
