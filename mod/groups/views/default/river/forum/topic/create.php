<?php

	$performed_by = get_entity($vars['item']->subject_guid); // $statement->getSubject();
	$object = get_entity($vars['item']->object_guid);
	//$url = $object->getURL();
	$forumtopic = $object->guid;
	$group_guid = $object->container_guid;
	$url = $vars['url'] . "mod/groups/topicposts.php?topic=" . $forumtopic . "&group_guid=" . $group_guid;
	$comment = $object->getAnnotations("group_topic_post", 1, 0, "asc"); 
	foreach($comment as $c){
		$contents = $c->value;
	}
	$contents = strip_tags($contents);//this is so we don't get large images etc in the activity river
	$url_user = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	$string = sprintf(elgg_echo("groupforum:river:postedtopic"),$url_user) . ": ";
	$string .= "<a href=\"" . $url . "\">" . $object->title . "</a>";
	$string .= "<div class=\"river_content_display\">";
	if(strlen($contents) > 200) {
        	$string .= substr($contents, 0, strpos($contents, ' ', 200)) . "...";
    	}else{
	    	$string .= $contents;
    	}
	$string .= "</div>";
	
?>

<?php echo $string; ?>