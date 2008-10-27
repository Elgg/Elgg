<?php

	/**
	 * Elgg Topic individual post view. This is all the follow up posts on a particular topic
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] The posted comment to view
	 */
	 
?>

	<div class="topic_post"><!-- start the topic_post -->
	
	    <table width="100%">
            <tr>
                <td>
                    <?php
                        //get infomation about the owner of the comment
                        if ($post_owner = get_user($vars['entity']->owner_guid)) {
	                        
	                        //display the user icon
	                        echo "<div class=\"post_icon\">" . elgg_view("profile/icon",array('entity' => $post_owner, 'size' => 'small')) . "</div>";
	                        
	                        //display the user name
	                        echo "<p><b>" . $post_owner->name . "</b><br />";
	                        
                        } else {
                        	echo "<p>";
                        }
                        
                        //display the date of the comment
                        echo "<small>" . friendly_time($vars['entity']->time_created) . "</small></p>";
                    ?>
                </td>
                <td width="70%">       
                    <?php
                        //display the actual message posted
                       echo "<p>" . parse_urls(elgg_view("output/longtext",array("value" => $vars['entity']->value))) . "</p>";
                    ?>
                </td>
            </tr>
        </table>
		<?php

		    //if the comment owner is looking at it, they can edit
			if ($vars['entity']->canEdit()) {
        ?>
		        <p class="topic-post-menu">
		        <?php
             				
			        echo elgg_view("output/confirmlink",array(
														'href' => $vars['url'] . "action/groups/deletepost?post=" . $vars['entity']->id . "&topic=" . get_input('topic') . "&group=" . get_input('group_guid'),
                										'text' => elgg_echo('delete'),
														'confirm' => elgg_echo('deleteconfirm'),
													));
		
		        ?>
		        </p>
		
        <?php
            }
	    ?>
		
	</div><!-- end the topic_post -->