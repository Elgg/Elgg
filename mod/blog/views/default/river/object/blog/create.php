<?php
/**
 * Blog river view.
 */

// @todo catch until riverdashboard plugin is updated
if ($vars['item'] instanceof stdClass) {
	echo "The riverdashboard plugin has not been updated yet to use the new ElggRiverItem Class";
	return true;
}

$object = $vars['item']->getObjectEntity();
$excerpt = strip_tags($object->excerpt);
$excerpt = elgg_get_excerpt($excerpt);

echo elgg_echo('blog:river:create');

echo " <a href=\"{$object->getURL()}\">{$object->title}</a>";

if ($excerpt) {
	echo '<div class="elgg-river-excerpt">';
	echo $excerpt;
	echo '</div>';
}
