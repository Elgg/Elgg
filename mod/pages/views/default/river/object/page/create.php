<?php

	$performed_by = get_entity($vars['item']->subject_guid); // $statement->getSubject();
	$object = get_entity($vars['item']->object_guid);
	$url = $object->getURL();

	
	$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	$contents = strip_tags($object->description); //strip tags from the contents to stop large images etc blowing out the river view
	$string = sprintf(elgg_echo("pages:river:created"),$url) . " ";
	$string .= elgg_echo("pages:river:create") . " <a href=\"" . $object->getURL() . "\">" . $object->title . "</a> <span class='entity_subtext'>". friendly_time($object->time_created) ."</span> <a class='river_comment_form_button link'>Comment</a>";
	$string .= elgg_view('likes/forms/link', array('entity' => $object));
	$string .= "<div class=\"river_content_display\">";
	$string .= elgg_make_excerpt($contents, 200);
	$string .= "</div>";

	echo $string;