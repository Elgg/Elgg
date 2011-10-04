<?php
/**
 * File icon view
 *
 * @uses $vars['entity']     The entity the icon represents - uses getIconURL() method
 * @uses $vars['size']       topbar, tiny, small, medium (default), large, master
 * @uses $vars['href']       Optional override for link
 * @uses $vars['link_class'] Optional CSS class added to img
 */

$entity = $vars['entity'];

$sizes = array('small', 'medium', 'large', 'tiny', 'master', 'topbar');
// Get size
if (!in_array($vars['size'], $sizes)) {
	$vars['size'] = "medium";
}

$title = $entity->title;

$url = $entity->getURL();
if (isset($vars['href'])) {
	$url = $vars['href'];
}

$class = '';
if ($entity->thumbnail) {
	$class = 'class="elgg-photo"';
}

$img_src = $entity->getIconURL($vars['size']);
$img_src = elgg_format_url($img_src);
$img = "<img $class src=\"$img_src\" alt=\"$title\" />";

if ($url) {
	$params = array(
		'href' => $url,
		'text' => $img,
	);
	if (isset($vars['link_class'])) {
		$params['class'] = $vars['link_class'];
	}
	echo elgg_view('output/url', $params);
} else {
	echo $img;
}
