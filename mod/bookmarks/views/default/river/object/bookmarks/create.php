<?php
/**
 * Elgg bookmark river entry view
 */

$performed_by = get_entity($vars['item']->subject_guid); // $statement->getSubject();
$object = get_entity($vars['item']->object_guid);
$url = $object->getURL();
$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
$string = sprintf(elgg_echo("bookmarks:river:created"),$url) . " ";
$string .= "<a href=\"" . $object->address . "\">" . $object->title . "</a> <span class='entity_subtext'>" . friendly_time($object->time_updated) . "</span>";
if (isloggedin()){
	$string .= "<a class='river_comment_form_button link'>Comment</a>";
	$string .= elgg_view('likes/forms/link', array('entity' => $object));
}
echo $string;