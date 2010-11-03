<?php
/**
 * Elgg bookmark river entry view
 */

$performed_by = get_entity($vars['item']->subject_guid); // $statement->getSubject();
$object = get_entity($vars['item']->object_guid);
$is_group = get_entity($object->container_guid);
$url = $object->getURL();
$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
$string = sprintf(elgg_echo("bookmarks:river:created"),$url) . " ";
$string .= "<a href=\"" . $object->address . "\">" . $object->title . "</a>";
if (($is_group instanceof ElggGroup) && (elgg_get_context() != 'groups')){
	$string .= " " . elgg_echo('bookmarks:ingroup') . " <a href=\"{$is_group->getURL()}\">" . $is_group->name . "</a>";
}
$string .= " <span class='entity_subtext'>" . elgg_view_friendly_time($object->time_created);
if (isloggedin()){
	$string .= "<a class='river_comment_form_button link'>Comment</a>";
	$string .= elgg_view('likes/forms/link', array('entity' => $object));
}
$string .= "</span>";
echo $string;