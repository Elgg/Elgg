<?php

	$performed_by = get_entity($vars['item']->subject_guid); // $statement->getSubject();
	$object = get_entity($vars['item']->object_guid);
	$objecturl = $object->getURL();

	$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
	$string = elgg_echo("groups:river:create", array($url)) . " ";
	$string .= " <a href=\"" . $object->getURL() . "\">" . $object->name . "</a>";
	$string .= " <span class='entity-subtext'>". elgg_view_friendly_time($object->time_created);
	if (isloggedin()) {
		$string .= elgg_view('forms/likes/link', array('entity' => $object));
	}
	$string .= "</span>";

echo $string;