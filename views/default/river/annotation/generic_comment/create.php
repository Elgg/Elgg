<?php
/**
 * Post comment river view
 */
$object = $vars['item']->getObjectEntity();
$comment = $vars['item']->getAnnotation();

$url = $object->getURL();
$title = $object->title;
if (!$title) {
	$title = elgg_echo('untitled');
}
$object_link = "<a href=\"{$object->getURL()}\">$title</a>";

$type = $object->getType();
$subtype = $object->getSubtype();

$type_string = elgg_echo("river:commented:$type:$subtype");
echo elgg_echo('river:generic_comment', array($type_string, $object_link));

if ($comment) {
	$excerpt = elgg_get_excerpt($comment->value);
	echo '<div class="elgg-river-excerpt">';
	echo $excerpt;
	echo '</div>';
}

/*
$string = elgg_echo("river:posted:generic", array($url)) . " ";
$string .= elgg_echo("{$subtype}:river:annotate") . "  <a href=\"{$object->getURL()}\">" . $title . "</a>";
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
 * 
 */