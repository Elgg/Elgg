<?php
/**
 * Generic icon view.
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['entity']     The entity the icon represents - uses getIconURL() method
 * @uses $vars['size']       topbar, tiny, small, medium (default), large, master
 * @uses $vars['href']       Optional override for link
 * @uses $vars['img_class']  Optional CSS class added to img
 */

$entity = $vars['entity'];

$sizes = array('small', 'medium', 'large', 'tiny', 'master', 'topbar');
// Get size
if (!in_array($vars['size'], $sizes)) {
	$vars['size'] = "medium";
}

$class = elgg_extract('img_class', $vars, '');

if (isset($entity->name)) {
	$title = $entity->name;
} else {
	$title = $entity->title;
}
$title = htmlspecialchars($title, ENT_QUOTES, 'UTF-8', false);

$url = $entity->getURL();
if (isset($vars['href'])) {
	$url = $vars['href'];
}

$img_src = $entity->getIconURL($vars['size']);
$img = "<img $class src=\"$img_src\" alt=\"$title\" />";

if ($url) {
	echo elgg_view('output/url', array(
		'href' => $url,
		'text' => $img,
		'is_trusted' => true,
	));
} else {
	echo $img;
}
