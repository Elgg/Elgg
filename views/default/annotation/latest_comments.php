<?php
/**
 * Display latest comments on objects
 **/
	 
if($vars['comments']){
	global $CONFIG;
	echo "<h3>" . elgg_echo('generic_comments:latest') . "</h3>";		
	foreach($vars['comments'] as $comment){
	   //grab the entity the comment is on
	   $entity = get_entity($comment->entity_guid);
		//comment owner
		$comment_owner = get_user($comment->owner_guid);
		$friendlytime = elgg_view_friendly_time($comment->time_created); // get timestamp for comment
	
		//set the title
		if($entity->title){
			$objecttitle = $entity->title;
		}else{
			$objecttitle = elgg_echo('untitled');
		}		
				
		//if the entity has been deleted, don't link to it
		if($entity){
			$url = $entity->getURL(); // get url to file for comment link
			$url_display = "<a href=\"{$url}\">{$objecttitle}</a>";
		}else{
			$url_display = $objecttitle;
		}
	
		echo "<div class='generic-comment latest clearfix'><span class='generic-comment-icon'>" . elgg_view("profile/icon",array('entity' => $comment_owner, 'size' => 'tiny')) . "</span>";
		echo "<div class='generic-comment-details'><span class='entity-subtext'><a href=\"".elgg_get_site_url()."pg/profile/{$comment_owner->username}\">{$comment_owner->name}</a> " . elgg_echo('on') . " <span class='entity-title'>{$url_display}</span> ({$friendlytime})</span></div>";
		echo "</div>";
	
	}
}