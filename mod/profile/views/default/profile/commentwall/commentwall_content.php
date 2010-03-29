<?php
/**
* Elgg Message board individual item display page
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
							'href' => $vars['url'] . "action/profile/deletecomment?annotation_id=" . $vars['annotation']->id,
								'text' => elgg_echo('delete'),
								'confirm' => elgg_echo('deleteconfirm'),
							)) . "</div>";
	    } //end of can edit if statement
	?>
	</div>
<div class="clearfloat"></div>
</div><!-- end of messageboard div -->
