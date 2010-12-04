<?php

$performed_by = get_entity($vars['item']->subject_guid);
$object = get_entity($vars['item']->object_guid);
$url = $object->getURL();
$title = $object->title;
if (!$title) {
	$title = elgg_echo('file:untitled');
}

$subtype = get_subtype_from_id($object->subtype);
//grab the annotation, if one exists
$comment = '';
if ($vars['item']->annotation_id != 0) {
	$comment = get_annotation($vars['item']->annotation_id)->value;
}

$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
$string = elgg_echo("river:posted:generic", array($url)) . " ";
$string .= elgg_echo("{$subtype}:river:annotate") . "  <a href=\"{$object->getURL()}\">" . $title . "</a> <span class='entity-subtext'>" . elgg_view_friendly_time($object->time_created) . "<a class='river_comment_form_button link'>Comment</a>";
$string .= elgg_view('likes/forms/link', array('entity' => $object));
$string .= "</span>";
if (elgg_get_context() != 'riverdashboard') {
	$comment = elgg_get_excerpt($comment, 200);
	if ($comment) {
		$string .= "<div class='river_content_display'>";
		$string .= $comment;
		$string .= "</div>";
	}
}
echo $string;