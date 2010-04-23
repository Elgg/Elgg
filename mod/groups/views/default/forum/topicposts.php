<?php

	/**
	 * Elgg Topic individual post view. This is all the follow up posts on a particular topic
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] The posted comment to view
	 */
	 
	
?>

<div class="entity_listing topic clearfloat">
<a name="<?php echo $vars['entity']->id; ?>"></a>
	<?php
	// get infomation about the owner of the comment
	if ($post_owner = get_user($vars['entity']->owner_guid)) {
	    // display the user icon
	    echo "<div class='entity_listing_icon'>" . elgg_view("profile/icon",array('entity' => $post_owner, 'size' => 'tiny')) . "</div>";
	    // display the user name
	    echo "<div class='entity_listing_info'>";
	    // if comment owner, group owner, or site admin - display edit and delete options
	    if (groups_can_edit_discussion($vars['entity'], page_owner_entity()->owner_guid)) {
			echo "<div class='entity_metadata'>";
	        echo "<span class='delete_button'>".elgg_view("output/confirmlink",array(
				'href' => $vars['url'] . "action/groups/deletepost?post=" . $vars['entity']->id . "&topic=" . get_input('topic') . "&group=" . get_input('group_guid'),
				'text' => elgg_echo('delete'),
				'confirm' => elgg_echo('deleteconfirm')
				))."</span>";
			echo "<span class='entity_edit'><a class='link' onclick=\"elgg_slide_toggle(this,'.topic','.edit_comment');\">".elgg_echo('edit')."</a></span>";
			echo "</div>";

		}	    
	    
	    echo "<p class='entity_title'>" . $post_owner->name . "</p>";
	} else {
		echo "<div class='entity_listing_icon'><img src=\"" . elgg_view('icon/user/default/tiny') . "\" /></div>";
		echo "<div class='entity_listing_info'><p class='entity_title'>" . elgg_echo('profile:deleteduser') . "</p>";
	}
	
	//display the date of the comment
	echo "<p class='entity_subtext'>" . friendly_time($vars['entity']->time_created) . "</p>";

	//display the actual message posted
	echo parse_urls(elgg_view("output/longtext",array("value" => $vars['entity']->value)));

    // if comment owner, group owner, or site admin - display edit-form
    if (groups_can_edit_discussion($vars['entity'], page_owner_entity()->owner_guid)) {
		//get the edit form and details
		$submit_input = elgg_view('input/submit', array('internalname' => 'submit', 'value' => elgg_echo('save')));
		$text_textarea = elgg_view('input/longtext', array('internalname' => 'postComment'.$vars['entity']->id, 'value' => $vars['entity']->value));
    	$post = elgg_view('input/hidden', array('internalname' => 'post', 'value' => $vars['entity']->id));
		$field = elgg_view('input/hidden', array('internalname' => 'field_num', 'value' => $vars['entity']->id));
    	$topic = elgg_view('input/hidden', array('internalname' => 'topic', 'value' => get_input('topic')));
		$group = elgg_view('input/hidden', array('internalname' => 'group', 'value' => get_input('group_guid')));
		$edittopic_title = elgg_echo('groups:edittopic');
			
		$form_body = <<<EOT
		
		<p class='longtext_inputarea'>
		<label>$edittopic_title</label>
		$text_textarea</p>
		$post
		$topic
		$group
		$field
		$submit_input
EOT;
		echo "<div class='edit_comment margin_top hidden'>";
		echo elgg_view('input/form', array('action' => "{$vars['url']}action/groups/editpost", 'body' => $form_body, 'internalid' => 'editforumpostForm'));
		echo "</div>";
    }
	echo "</div>"; // close entity_listing_info
?>
		
</div>