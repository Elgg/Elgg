<?php

	/**
	 * Elgg Topic individual post view. This is all the follow up posts on a particular topic
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] The posted comment to view
	 */
	 
	
?>

	<div class="topic_post"><!-- start the topic_post -->
	
	    <table width="100%">
            <tr>
                <td>
                	<a name="<?php echo $vars['entity']->id; ?>"></a>
                    <?php
                        //get infomation about the owner of the comment
                        if ($post_owner = get_user($vars['entity']->owner_guid)) {
	                        
	                        //display the user icon
	                        echo "<div class=\"post_icon\">" . elgg_view("profile/icon",array('entity' => $post_owner, 'size' => 'small')) . "</div>";
	                        
	                        //display the user name
	                        echo "<p><b>" . $post_owner->name . "</b><br />";
	                        
                        } else {
                        	echo "<div class=\"post_icon\"><img src=\"" . elgg_view('icon/user/default/small') . "\" /></div>";
                        	echo "<p><b>" . elgg_echo('profile:deleteduser') . "</b><br />";
                        }
                        
                        //display the date of the comment
                        echo "<small>" . friendly_time($vars['entity']->time_created) . "</small></p>";
                    ?>
                </td>
                <td width="70%">       
                    <?php
                        //display the actual message posted
                       echo parse_urls(elgg_view("output/longtext",array("value" => $vars['entity']->value)));
                    ?>
                </td>
            </tr>
        </table>
		<?php

		    //if the comment owner is looking at it, or admin, or group owner they can edit
		    if (groups_can_edit_discussion($vars['entity'], page_owner_entity()->owner_guid)) {
        ?>
		        <p class="topic-post-menu">
		        <?php
             				
			        echo elgg_view("output/confirmlink",array(
														'href' => $vars['url'] . "action/groups/deletepost?post=" . $vars['entity']->id . "&topic=" . get_input('topic') . "&group=" . get_input('group_guid'),
                										'text' => elgg_echo('delete'),
														'confirm' => elgg_echo('deleteconfirm'),
													));
						
					//display an edit link that will open up an edit area							
					echo " <a class=\"collapsibleboxlink\">".elgg_echo('edit')."</a>";
					echo "<div class=\"collapsible_box\">";
					//get the edit form and details
					$submit_input = elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('save')));
					$text_textarea = elgg_view('input/longtext', array('internalname' => 'postComment'.$vars['entity']->id, 'value' => $vars['entity']->value));
                	$post = elgg_view('input/hidden', array('internalname' => 'post', 'value' => $vars['entity']->id));
		  			$field = elgg_view('input/hidden', array('internalname' => 'field_num', 'value' => $vars['entity']->id));
                	$topic = elgg_view('input/hidden', array('internalname' => 'topic', 'value' => get_input('topic')));
		  			$group = elgg_view('input/hidden', array('internalname' => 'group', 'value' => get_input('group_guid')));
		  			
					$form_body = <<<EOT
					
					<div class='edit_forum_comments'>
					<p class='longtext_editarea'>	
						$text_textarea
					</p>
					$post
					$topic
					$group
					$field
					<p>
						$submit_input
					</p>
						
					</div>
					
EOT;
				
?>

				<?php
					echo elgg_view('input/form', array('action' => "{$vars['url']}action/groups/editpost", 'body' => $form_body, 'internalid' => 'editforumpostForm'));
				?>
					</div>
		        </p>
		
        <?php
            }
	    ?>
		
	</div><!-- end the topic_post -->