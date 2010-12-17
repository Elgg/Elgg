<?php
/**
 * Group creation river view.
 */

$object = $vars['item']->getObjectEntity();
$excerpt = strip_tags($object->description);
$excerpt = elgg_get_excerpt($excerpt);

$params = array(
	'href' => $object->getURL(),
	'text' => $object->name,
);
$link = elgg_view('output/url', $params);


echo elgg_echo('groups:river:create');

echo " $link";

if ($excerpt) {
	echo '<div class="elgg-river-content">';
	echo $excerpt;
	echo '</div>';
}
