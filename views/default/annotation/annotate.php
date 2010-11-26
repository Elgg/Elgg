<?php
/**
 * Elgg comment river view 
 *
 * @package Elgg
 * @subpackage Core
 */

$performed_by = get_entity($vars['item']->subject_guid);
$object = get_entity($vars['item']->object_guid);
$url = $object->getURL();
$title = $object->title;
if (!$title) {
	$title = elgg_echo('untitled');
}
$subtype = get_subtype_from_id($object->subtype);

//grab the annotation, if one exists
$comment = '';
if ($vars['item']->annotation_id != 0) {
	$comment = get_annotation($vars['item']->annotation_id)->value;
}

$url = "<a href=\"{$performed_by->getURL()}\">{$performed_by->name}</a>";
$string = sprintf(elgg_echo("river:posted:generic"),$url) . " ";
$string .= elgg_echo("{$subtype}:river:annotate") . " | <a href=\"{$object->getURL()}\">" . $title . "</a>";

$comment = elgg_get_excerpt($comment, 200);
if ($comment) {
	$string .= "<div class=\"river_content_display\">";
	$string .= $comment;
	$string .= "</div>";
}


echo $string;