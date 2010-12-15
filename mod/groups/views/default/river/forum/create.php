<?php
/**
 * Any comment on original post
 */

	$performed_by = get_entity($vars['item']->subject_guid);
	$object = get_entity($vars['item']->object_guid);
	$object_url = $object->getURL();

	$forumtopic = $object->guid;
	$group_guid = $object->container_guid;
	//grab the annotation, if one exists
	if($vars['item']->annotation_id != 0) {
		$comment = get_annotation($vars['item']->annotation_id)->value;
	}
	$comment = strip_tags($comment);//this is so we don't get large images etc in the activity river
	$url = elgg_get_site_url() . "mod/groups/topicposts.php?topic=" . $forumtopic . "&group_guid=" . $group_guid;
	$url_user = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	$string = elgg_echo("groupforum:river:posted", array($url_user)) . " ";
	$string .= elgg_echo("groupforum:river:annotate:create") . " | <a href=\"" . $url . "\">" . $object->title . "</a> <span class='entity-subtext'>". elgg_view_friendly_time($object->time_created) ."<a class='river_comment_form_button link' href=\"{$object_url}\">Visit discussion</a>";
	$string .= elgg_view('forms/likes/link', array('entity' => $object));
	$string .= "</span>";
	if ($comment) {
		$string .= "<div class=\"river_content_display\">";
		$string .= elgg_get_excerpt($comment, 200);
		$string .= "</div>";
	}

	echo $string;
