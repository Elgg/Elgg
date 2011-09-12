<?php
/**
 * Generic icon view.
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['entity'] The entity the icon represents - uses getIconURL() method
 * @uses $vars['size']   topbar, tiny, small, medium (default), large, master
 * @uses $vars['href']   Optional override for link
 */

$entity = $vars['entity'];

$sizes = array('small', 'medium', 'large', 'tiny', 'master', 'topbar');
// Get size
if (!in_array($vars['size'], $sizes)) {
	$vars['size'] = "medium";
}

if (isset($entity->name)) {
	$title = $entity->name;
} else {
	$title = $entity->title;
}

$url = $entity->getURL();
if (isset($vars['href'])) {
	$url = $vars['href'];
}

$img_src = $entity->getIconURL($vars['size']);

$icon_dimensions = array(
    'topbar' => 16,
    'tiny' => 25,
    'small' => 40,
    'medium' => 100,
    'large' => 200,
);

$dimension = $icon_dimensions[$vars['size']];

if ($dimension) {
	$img = "<img src=\"$img_src\" alt=\"$title\" width=\"${dimension}px\" height=\"${dimension}px\" />";
}
else {
	$img = "<img src=\"$img_src\" alt=\"$title\" />";
}

if ($url) {
	echo elgg_view('output/url', array(
		'href' => $url,
		'text' => $img,
	));
} else {
	echo $img;
}
