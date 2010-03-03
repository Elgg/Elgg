<?php

     /**
	 * Elgg Message board individual item display page
	 * 
	 * @package ElggMessageBoard
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */
	 
?>

<div class="messageboard"><!-- start of messageboard div -->
	
    <!-- display the user icon of the user that posted the message -->
    <div class="message_sender">	        
        <?php
            echo elgg_view("profile/icon",array('entity' => get_entity($vars['annotation']->owner_guid), 'size' => 'tiny'));
        ?>
    </div>
    
    <!-- display the user's name who posted and the date/time -->
    <p class="message_item_timestamp">
        <?php echo get_entity($vars['annotation']->owner_guid)->name . " " . friendly_time($vars['annotation']->time_created); ?>
    </p>
    		
	<!-- output the actual comment -->
	<div class="message"><?php echo elgg_view("output/longtext",array("value" => parse_urls($vars['annotation']->value))); ?></div>
	<div class="message_buttons">
		    
	<?php
               
        // if the user looking at the comment can edit, show the delete link
	    if ($vars['annotation']->canEdit()) {
    			    
  
			       echo "<div class='delete_message'>" . elgg_view("output/confirmlink",array(
							'href' => $vars['url'] . "action/messageboard/delete?annotation_id=" . $vars['annotation']->id,
								'text' => elgg_echo('delete'),
								'confirm' => elgg_echo('deleteconfirm'),
							)) . "</div>";
		
	    } //end of can edit if statement
	?>
<?php
		            // If the message being looked at is owned by the current user, don't show the reply
		            // Also do not show if the user is not logged in  
		            if (isloggedin() && $vars['annotation']->owner_guid != get_loggedin_userid()){
    		            
    		            //get the message owner
    		            $msg_owner = get_entity($vars['annotation']->owner_guid);
    		            //create the url to their messageboard
    		            $user_mb = "pg/messageboard/" . $msg_owner->username;
    		            
    		            echo "<a href=\"" . $vars['url'] . $user_mb . "\">".elgg_echo('messageboard:replyon')." " . $msg_owner->name . "'s " . elgg_echo('messageboard:messageboard') . "</a> | ";
    		            
    		            echo "<a href=\"" . $vars['url'] . "mod/messageboard/history.php?user=" . $msg_owner->guid ."\">" . elgg_echo('messageboard:history') . "</a>"; 
		            }
?>
		        
		  </div>
	<div class="clearfloat"></div>
</div><!-- end of messageboard div -->
