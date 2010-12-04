<?php
/**
* Elgg Message board individual item display page
 */
?>
<div class="entity-listing clearfix">
    <!-- display the user icon of the user that posted the message -->
    <div class="entity-listing-icon">	        
        <?php
            echo elgg_view("profile/icon",array('entity' => get_entity($vars['annotation']->owner_guid), 'size' => 'tiny'));
        ?>
    </div>
    
    <div class="entity-listing-info">
	<?php
        // if the user looking at the comment can edit, show the delete link
	    if ($vars['annotation']->canEdit()) {
			       echo "<div class='entity-metadata'><span class='delete-button'>" . elgg_view("output/confirmlink",array(
							'href' => "action/profile/deletecomment?annotation_id=" . $vars['annotation']->id,
								'text' => elgg_echo('delete'),
								'confirm' => elgg_echo('deleteconfirm'),
							)) . "</span></div>";
	    } //end of can edit if statement
	?>
	    <!-- display the user's name who posted and the date/time -->
	    <p class="entity-subtext">
	        <?php echo get_entity($vars['annotation']->owner_guid)->name . " " . elgg_view_friendly_time($vars['annotation']->time_created); ?>
	    </p>	
		<!-- output the actual comment -->
		<?php echo elgg_view("output/longtext",array("value" => parse_urls($vars['annotation']->value))); ?>  
	</div>
</div>
