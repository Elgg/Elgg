<?php

	/**
	 * Elgg generic comment
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 * 
	 */

?>


	    <div class="generic_comment"><!-- start of generic_comment div -->
	    
	        <div style="float:left;width:60px;">	        
    			    <?php
    					    echo elgg_view("profile/icon",array('entity' => get_entity($vars['annotation']->owner_guid), 'size' => 'tiny'));
    			    ?>
    	    </div>
    	    <p>
    	        <?php echo get_user($vars['annotation']->owner_guid)->username . " on " . friendly_time($vars['annotation']->time_created); ?>
    		</p>
    		
		    <!-- output the actual comment -->
		    <p><?php echo elgg_view("output/longtext",array("value" => $vars['annotation']->value)); ?></p>
		    
		    <?php
                
		        // if the user looking at the comment can edit, show the delete link
			    if ($vars['annotation']->canEdit()) {
    			    
            ?>
		    <p>
		        <?php

			        echo elgg_view("output/confirmlink",array(
														'href' => $vars['url'] . "action/comments/delete&annotation_id=" . $vars['annotation']->id,
														'text' => elgg_echo('delete'),
														'confirm' => elgg_echo('deleteconfirm'),
													));
		
		        ?>
		    </p>
		
            <?php
			    } //end of can edit if statement
		    ?>
		
		</div><!-- end of generic_comment div -->