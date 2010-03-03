<?php
/**
 * Display latest comments on objects
 **/
	 
if($vars['comments']){
	global $CONFIG;
		
	echo "<div class='sidebar container'>";
	echo "<h2>" . elgg_echo('latestcomments') . "</h2>";		
	foreach($vars['comments'] as $comment){
	   //grab the entity the comment is on
	   $entity = get_entity($comment->entity_guid);
		//comment owner
		$comment_owner = get_user($comment->owner_guid);
		$friendlytime = friendly_time($comment->time_created); // get timestamp for comment
	
		//set the title
		if($entity->title)
			$objecttitle = $entity->title;
		else
			$objecttitle = elgg_echo('file:untitled');			
				
		//if the entity has been deleted, don't link to it
		if($entity){
			$url = $entity->getURL(); // get url to file for comment link
			$url_display = "<a href=\"{$url}\">{$objecttitle}</a>";
			//$owner = $entity->getOwnerEntity(); // get file owner
		}else{
			$url_display = $objecttitle;
		}
	
		echo "<div class='LatestComment'><span class='generic_comment_icon'>" . elgg_view("profile/icon",array('entity' => $comment_owner, 'size' => 'tiny')) . "</span>";
		echo "<p class='owner_timestamp'><a href=\"{$vars['url']}pg/profile/{$comment_owner->username}\">{$comment_owner->name}</a> " . elgg_echo('on') . " {$url_display} <small>{$friendlytime}</small></p>";
		echo "<div class='clearfloat'></div></div>";
	
	}
	echo "</div>";
}