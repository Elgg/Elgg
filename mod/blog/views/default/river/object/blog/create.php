<?php
/**
 * Blog river view.
 */

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
