<?php
/**
 * Messageboard river view
 */

$performed_by = $vars['item']->getSubjectEntity();
$performed_on = $vars['item']->getObjectEntity();

$comment = $vars['item']->getAnnotation();

$link = elgg_view('output/url', array(
	'href' => $performed_on->getURL(),
	'text' => elgg_echo('messageboard:river:user', array($performed_on->name)),
));

echo elgg_echo("messageboard:river:added");
echo " $link ";
echo elgg_echo("messageboard:river:messageboard");

if ($comment) {
	echo '<div class="elgg-river-content">';
	echo elgg_get_excerpt($comment->value);
	echo '</div>';
}
