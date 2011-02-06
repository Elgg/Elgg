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

$url = $entity->getURL();
if (isset($vars['href'])) {
	$url = $vars['href'];
}

$img_src = $entity->getIcon($vars['size']);
$img = "<img src=\"$img_src\" />";

if ($url) {
	echo elgg_view('output/url', array(
		'href' => $url,
		'text' => $img,
	));
} else {
	echo $img;
}
