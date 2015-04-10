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
 * @uses $vars['link_class'] Optional CSS class for the link
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggEntity) {
	return;
}

$icon_sizes = elgg_get_config('icon_sizes');
if (!isset($vars['size']) || !array_key_exists($vars['size'], $icon_sizes)) {
	$vars['size'] = 'medium';
}

if ($vars['size'] != 'master') {
	$dimensions = elgg_extract($vars['size'], $icon_sizes, array());
	$width = elgg_extract('w', $dimensions);
	$height = elgg_extract('h', $dimensions);
}

$img = elgg_view('output/img', array(
	'src' => $entity->getIconURL($vars),
	'alt' => $entity->getDisplayName(),
	'class' => elgg_extract('img_class', $vars),
	'width' => elgg_extract('width', $vars, $width),
	'height' => elgg_extract('height', $vars, $height),
));

$url = elgg_extract('href', $vars, $entity->getURL());

if ($url) {
	echo elgg_view('output/url', array(
		'href' => $url,
		'text' => $img,
		'is_trusted' => true,
		'class' => elgg_extract('link_class', $vars),
	));
} else {
	echo $img;
}
