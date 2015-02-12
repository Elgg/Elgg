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
if (!$entity instanceof \ElggFile) {
	return;
}

$class = array();
if ($entity->thumbnail) {
	$class[] = 'elgg-photo';
}
if (isset($vars['img_class'])) {
	$class[] = $vars['img_class'];
}

$vars['img_class'] = implode(' ', $class);

echo elgg_view('icon/default', $vars);
