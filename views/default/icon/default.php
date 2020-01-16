<?php
/**
 * Generic icon view.
 *
 * @uses $vars['entity']     The entity the icon represents - uses getIconURL() method
 * @uses $vars['size']       topbar, tiny, small, medium (default), large, master
 * @uses $vars['use_link']   Hyperlink the icon
 * @uses $vars['href']       Optional override for link
 * @uses $vars['img_class']  Optional CSS class added to img
 * @uses $vars['link_class'] Optional CSS class for the link
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

$icon_sizes = elgg_get_icon_sizes($entity->type, $entity->getSubtype());
// Get size
$size = elgg_extract('size', $vars, 'medium');
if (!array_key_exists($size, $icon_sizes)) {
	$size = "medium";
}
$vars['size'] = $size;

$class = elgg_extract('img_class', $vars, '');

$title = htmlspecialchars($entity->getDisplayName(), ENT_QUOTES, 'UTF-8', false);

$url = false;
if (elgg_extract('use_link', $vars, true)) {
	$url = elgg_extract('href', $vars, $entity->getURL());
}

if (!isset($vars['width'])) {
	$vars['width'] = $size != 'master' ? $icon_sizes[$size]['w'] : null;
}
if (!isset($vars['height'])) {
	$vars['height'] = $size != 'master' ? $icon_sizes[$size]['h'] : null;
}

$img_params = [
	'src' => $entity->getIconURL($size),
	'alt' => $title,
];

if (!empty($class)) {
	$img_params['class'] = $class;
}

if (!empty($vars['width'])) {
	$img_params['width'] = elgg_extract('width', $vars);
}

if (!empty($vars['height'])) {
	$img_params['height'] = elgg_extract('height', $vars);
}

$img = elgg_view('output/img', $img_params);
if (empty($img)) {
	return;
}

if ($url) {
	$params = [
		'href' => $url,
		'text' => $img,
		'is_trusted' => true,
	];
	$class = elgg_extract('link_class', $vars, '');
	if ($class) {
		$params['class'] = $class;
	}

	echo elgg_view('output/url', $params);
} else {
	echo $img;
}
