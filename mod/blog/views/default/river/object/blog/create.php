<?php

	$performed_by = get_entity($vars['item']->subject_guid); // $statement->getSubject();
	$object = get_entity($vars['item']->object_guid);
	$url = $object->getURL();
	
	$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	$contents = strip_tags($object->description); //strip tags from the contents to stop large images etc blowing out the river view
	$string = sprintf(elgg_echo("blog:river:created"),$url) . " ";
	$string .= elgg_echo("blog:river:create") . " <a href=\"" . $object->getURL() . "\">" . $object->title . "</a>";
	$string .= "<div class=\"river_content_display\">";
	$string .= elgg_get_excerpt($contents, 200);
	$string .= "</div>";

	echo $string;
?>