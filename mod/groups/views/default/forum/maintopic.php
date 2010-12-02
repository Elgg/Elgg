<?php

	/**
	 * Elgg Topic individual post view.
	 * 
	 * @package ElggGroups
	 * 
	 * @uses $vars['entity'] The post
	 */
	 
	 $topic = get_input('topic');
	 $group_guid = get_input('group_guid');
	
?>

<div class="entity_listing topic clearfix">
<a class="anchor_link" name="<?php echo $vars['entity']->id; ?>"></a>
	<?php
	// get infomation about the owner of the comment
	if ($post_owner = get_user($vars['entity']->owner_guid)) {
	    // display the user icon
	    echo "<div class='entity_listing_icon'>" . elgg_view("profile/icon",array('entity' => $post_owner, 'size' => 'tiny')) . "</div>";
	    // display the user name
	    echo "<div class='entity_listing_info'>";
	    // if comment owner, group owner, or site admin - display edit and delete options
	    if (groups_can_edit_discussion($vars['entity'], elgg_get_page_owner()->owner_guid)) {
			echo "<div class='entity_metadata'>";
	        echo "<span class='delete-button'>".elgg_view("output/confirmlink",array(
				'href' => "action/groups/deletepost?post=" . $vars['entity']->id . "&topic=" . get_input('topic') . "&group=" . get_input('group_guid'),
				'text' => elgg_echo('delete'),
				'confirm' => elgg_echo('deleteconfirm')
				))."</span>";
			echo "<span class='entity_edit'><a class='link' href=\"".elgg_get_site_url()."pg/groups/edittopic/{$group_guid}/{$topic}/\">".elgg_echo('edit')."</a></span>";
			echo "</div>";

		}	    
	    
	    echo "<p class='entity_title'><a href='".$post_owner->getURL()."'>" . $post_owner->name . "</a></p>";
	} else {
		echo "<div class='entity_listing_icon'><img src=\"" . elgg_view('icon/user/default/tiny') . "\" /></div>";
		echo "<div class='entity_listing_info'><p class='entity_title'>" . elgg_echo('profile:deleteduser') . "</p>";
	}
	
	//display the date of the comment
	echo "<p class='entity_subtext'>" . elgg_view_friendly_time($vars['entity']->time_created) . "</p>";
	echo "</div>"; // close entity_listing_info
	echo "</div>"; // close entity_listing.topic

	//display the actual message posted
	echo "<div class='topic_post maintopic'>";
	echo parse_urls(elgg_view("output/longtext",array("value" => $vars['entity']->description)));
	echo "</div>";
?>
		
<!-- </div> -->