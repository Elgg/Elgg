<?php
	/**
	 * Elgg groups plugin
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */
	 
?>

<div id="content_area_group_title"><h2><?php echo elgg_echo("groups:forum"); ?></h2></div>

<?php
    //only show the add link if the user is a member
    if(page_owner_entity()->isMember($vars['user'])){
     
?>
        <!-- display the add a topic link -->
        <a href="<?php echo $vars['url']; ?>mod/groups/addtopic.php?group_guid=<?php echo get_input('group_guid'); ?>" class="add_topic_button"><?php echo elgg_echo("groups:addtopic"); ?></a>

<?php
    }
?> 

<div id="forum_topics"><!-- start of the forum_topics div -->
    
  
        <!-- display the forum title -->
        <h2><?php echo get_entity(get_input("forum"))->title; ?></h2>
        
        <!-- display the column heading for a forum's topics -->
        <table width="100%" border="0" id="topic_titles">
            <tr>
                <td><b><?php echo elgg_echo("groups:topic"); ?></b></td>
                <td width="70px"><b><?php echo elgg_echo("groups:posts"); ?></b></td>
                <td width="130px"><b><?php echo elgg_echo("groups:lastperson"); ?></b></td>
                <td width="100px"><b><?php echo elgg_echo("groups:when"); ?></b></td>
            </tr>
        </table>
     

<?php
	// If there are any topics to view, view them
		if (is_array($vars['entity']) && sizeof($vars['entity']) > 0) {		

			foreach($vars['entity'] as $topic) {
				
    			//This function controls the alternating background on table cells for topics
                $even_odd = ( 'odd' != $even_odd ) ? 'odd' : 'even';
                //get the last reply annotation posted and the user who posted it
                //this is used to display the time and person who made the last post
                $last_post = $topic->getAnnotations("group_topic_post", 1, 0, "desc");
                //get the time and user
                foreach($last_post as $last){
                    $last_time = $last->time_created;
                    $last_user = $last->owner_guid;
                }
                        
                //display the divs
                echo "<div class=\"forum_topics\"><div class=\"{$even_odd}\">";
?>       
                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td>
                        <p class="topic_title"><a href="<?php echo $vars['url']; ?>mod/groups/topicposts.php?topic=<?php echo $topic->guid; ?>&group_guid=<?php echo $topic->container_guid; ?>"><?php echo $topic->title; ?></a></p>
                        <!-- display edit and delete links if the user has privileges -->
        		        <?php
        
                		    // check to see if the current user can edit
                			if ($topic->canEdit()) {
                				
                		?>
                				<a href="<?php echo $vars['url']; ?>mod/groups/edittopic.php?topic=<?php echo $topic->guid; ?>&group=<?php echo $topic->container_guid; ?>"><?php echo elgg_echo("edit"); ?></a> &nbsp; 
                				<?php
                				 
                				    // display the delete link to those allowed to delete
                					echo elgg_view("output/confirmlink", array(
                																'href' => $vars['url'] . "action/groups/deletetopic?topic=" . $topic->getGUID() . "&group=" . $topic->container_guid,
                																'text' => elgg_echo('delete'),
                																'confirm' => elgg_echo('deleteconfirm'),
                															));
                				
                				?>
                		<?php
                			}
                		
                		?>
        		    </td>
        		    <td width="70px" valign="top">
                        <p><?php 
                                echo ($topic->countAnnotations("group_topic_post")); 
                         ?></p>
                    </td>
                    <td width="130px" valign="top">
                        <p>
                            <?php
                                //display the last user to post
                                if ($u = get_user($last_user))
                                	echo $u->name;
                             ?>
                        </p>
                    </td>
                    <td width="100px" valign="top">
                        <p><small>
                            <?php
                                //display the time of the last post
                                echo friendly_time($last_time); 
                            ?>
                        </small></p>
                    </td>
				</tr>
				</table>
				
				<!-- close the two display divs -->
				</div></div>
				
<?php	
			}
	    	
		} else {
    		
    		// if there are no topics, display a message saying so
    		echo "<p>". elgg_echo("grouptopic:notcreated") . "</p>";
    		
		}

?>

	</div><!-- close the forum_topics div -->