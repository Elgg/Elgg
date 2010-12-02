<?php

	$performed_by = get_entity($vars['item']->subject_guid); // $statement->getSubject();
	$object = get_entity($vars['item']->object_guid);
	$object_url = $object->getURL();
	$forumtopic = $object->guid;
	$group_guid = $object->container_guid;
	$group = get_entity($group_guid);
	$url = elgg_get_site_url() . "mod/groups/topicposts.php?topic=" . $forumtopic . "&group_guid=" . $group_guid;
	//$comment = $object->getAnnotations("group_topic_post", 1, 0, "asc");
	//foreach($comment as $c){
	$contents = $object->description;
	//}
	$contents = strip_tags($contents);//this is so we don't get large images etc in the activity river
	$url_user = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	$string = elgg_echo("groupforum:river:postedtopic", array($url_user)) . ": ";
	$string .= "<a href=\"" . $url . "\">" . $object->title . "</a>";
	$string .= " " . elgg_echo('groups:ingroup') . " <a href=\"{$group->getURL()}\">" . $group->name . "</a>";
	$string .= " <span class='entity-subtext'>". elgg_view_friendly_time($object->time_created);
	if (isloggedin() && $object->status != "closed") {
		$string .= '<a class="river_comment_form_button link">' . elgg_echo('generic_comments:text') . '</a>';
		$string .= elgg_view('likes/forms/link', array('entity' => $object));
	}
	$string .= "</span>";
	$string .= "<div class=\"river_content_display\">";
	$string .= elgg_get_excerpt($contents, 200);
	$string .= "</div>";

	echo $string;