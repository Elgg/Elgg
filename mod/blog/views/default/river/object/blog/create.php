<?php
/**
 * Blog river view.
 */

$object = $vars['item']->getObjectEntity();
$excerpt = strip_tags($object->excerpt);
$excerpt = elgg_get_excerpt($excerpt);

$params = array(
	'href' => $object->getURL(),
	'text' => $object->title,
);
$link = elgg_view('output/url', $params);

$group_string = '';
$container = $object->getContainerEntity();
if ($container instanceof ElggGroup) {
	$params = array(
		'href' => $container->getURL(),
		'text' => $container->name,
	);
	$group_link = elgg_view('output/url', $params);
	$group_string = elgg_echo('river:ingroup', array($group_link));
}

echo elgg_echo('blog:river:create');

echo " $link $group_string";

if ($excerpt) {
	echo '<div class="elgg-river-content">';
	echo $excerpt;
	echo '</div>';
}
