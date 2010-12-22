<?php

    /**
	 * Elgg Groups topic edit/add page
	 * 
	 * @package ElggGroups
	 * 
	 * @uses $vars['entity'] Optionally, the topic to edit
	 */
	 
	 //users can edit the access and status for now
	    $access_id = $vars['entity']->access_id;
	    $status = $vars['entity']->status;
	    $tags = $vars['entity']->tags;
	    $title = $vars['entity']->title;
	    $message = $vars['entity']->description;		    
		    
	 // get the group GUID
	    $group_guid = get_input("group");
	    
	// topic guid
	    $topic_guid = $vars['entity']->guid;
	    
	// set the title
	    echo elgg_view_title(elgg_echo("groups:edittopic"));
	 
?>
<!-- display the input form -->
	<form id="group_edittopic" action="<?php echo elgg_get_site_url(); ?>action/groups/edittopic" method="post">
	<?php echo elgg_view('input/securitytoken'); ?>
	
		<p>
			<label><?php echo elgg_echo("title"); ?><br />
			<?php
                //display the topic title input
				echo elgg_view("input/text", array(
									"internalname" => "topictitle",
									"value" => $title,
													));
			?>
			</label>
		</p>
		
		<!-- display the tag input -->
		<p>
			<label><?php echo elgg_echo("tags"); ?><br />
			<?php

				echo elgg_view("input/tags", array(
									"internalname" => "topictags",
									"value" => $tags,
													));
			
			?>
			</label>
		</p>
		
		<!-- topic message input -->
		<p class="longtext_inputarea">
			<label><?php echo elgg_echo("groups:topicmessage"); ?></label>
			<?php

				echo elgg_view("input/longtext",array(
									"internalname" => "topicmessage",
									"value" => html_entity_decode($message, ENT_COMPAT, 'UTF-8')
													));
			?>
		</p>
		
		<!-- set the topic status -->
		<p>
		    <label><?php echo elgg_echo("groups:topicstatus"); ?><br />
		    <select name="status">
		        <option value="open" <?php if($status == "") echo "SELECTED";?>><?php echo elgg_echo('groups:topicopen'); ?></option>
		        <option value="closed" <?php if($status == "closed") echo "SELECTED";?>><?php echo elgg_echo('groups:topicclosed'); ?></option>
		    </select>
		    </label>
		</p>
		
		<!-- access -->
		<p>
			<label>
				<?php echo elgg_echo('access'); ?><br />
				<?php echo elgg_view('input/access', array('internalname' => 'access_id','value' => $access_id)); ?>
			</label>
		</p>
		
		<!-- required hidden info and submit button -->
		<p>
			<input type="hidden" name="group_guid" value="<?php echo $group_guid; ?>" />
			<input type="hidden" name="topic" value="<?php echo $topic_guid; ?>" />
			<input type="hidden" name="message_id" value="<?php echo $message_id; ?>" />
			<?php echo elgg_view('input/submit', array('value' => elgg_echo('save'))); ?>
		</p>
	
	</form>