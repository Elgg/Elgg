<?php
/**
 * File icon view
 *
 * @uses $vars['entity']     The entity the icon represents - uses getIconURL() method
 * @uses $vars['size']       topbar, tiny, small, medium (default), large, master
 * @uses $vars['use_link']   Hyperlink the icon
 * @uses $vars['href']       Optional override for link
 * @uses $vars['img_class']  Optional CSS class added to img
 * @uses $vars['link_class'] Optional CSS class added to link
 */
$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggFile) {
	return;
}

$size = elgg_extract('size', $vars, 'medium');

if (elgg_extract('use_link', $vars, true)) {
	$url = elgg_extract('href', $vars, $entity->getURL());
}

$class = [];
if (isset($vars['img_class'])) {
	$class[] = $vars['img_class'];
}

if ($entity->hasIcon($size)) {
	$class[] = 'elgg-photo';
}

$img = elgg_view('output/img', [
	'class' => $class,
	'alt' => $entity->getDisplayName(),
	'src' => $entity->getIconURL($size),
]);
if (!$url) {
	echo $img;
	return;
}

echo elgg_view('output/url', [
	'href' => $url,
	'text' => $img,
	'is_trusted' => true,
	'class' => elgg_extract('link_class', $vars),
]);
