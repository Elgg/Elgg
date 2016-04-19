<?php
/**
 * File icon view
 *
 * @uses $vars['entity']     The entity the icon represents - uses getIconURL() method
 * @uses $vars['size']       topbar, tiny, small, medium (default), large, master
 * @uses $vars['href']       Optional override for link
 * @uses $vars['img_class']  Optional CSS class added to img
 * @uses $vars['link_class'] Optional CSS class added to link
 */
$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggFile) {
	elgg_log('icon/object/file view expects an instance of ElggFile', 'ERROR');
	return;
}

$sizes = array_keys(elgg_get_icon_sizes($entity->getType(), $entity->getSubtype()));
$size = elgg_extract('size', $vars, 'medium');
if (!in_array($size, $sizes)) {
	// File plugin only capable of handling 3 sizes
	// Anything that is an unknown size defaults to large
	if ($size == 'topbar' || $size == 'tiny') {
		$size = 'small';
	} else if ($size == 'master') {
		$size = 'large';
	} else {
		$size = "medium";
	}
}

if (isset($vars['href'])) {
	$url = $vars['href'];
} else {
	$url = $entity->getURL();
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
if ($url) {
	$params = array(
		'href' => $url,
		'text' => $img,
		'is_trusted' => true,
	);
	if (isset($vars['link_class'])) {
		$params['class'] = $vars['link_class'];
	}
	echo elgg_view('output/url', $params);
} else {
	echo $img;
}