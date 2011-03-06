<?php
/**
 * Group forum topic create river view.
 */

$object = $vars['item']->getObjectEntity();
$excerpt = strip_tags($object->description);
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

echo elgg_echo('forumtopic:river:create');

echo " $link $group_string";

if ($excerpt) {
	echo '<div class="elgg-river-content">';
	echo $excerpt;
	echo '</div>';
}

if (elgg_is_logged_in() && $container->isMember(elgg_get_logged_in_user_entity())) {
	// inline comment form
	echo elgg_view_form('discussion/reply/save', array(
		'id' => "groups-reply-{$object->getGUID()}",
	), array('entity' => $object, 'inline' => true));
}
