<?php
/**
 * Page icon
 *
 * Uses a separate icon view due to dependency on annotation
 *
 * @package ElggPages
 *
 * @uses $vars['entity']
 * @uses $vars['annotation']
 */

$annotation = $vars['annotation'];
$entity = get_entity($annotation->entity_guid);

// Get size
$size = elgg_extract('size', $vars, 'medium');
$sizes = elgg_get_icon_sizes('object', 'page_top');
if (!array_key_exists($size, $sizes)) {
	$size = 'medium';
}

$icon_vars = [
	'style' => [
		'width: ' . $sizes[$size]['w'] . 'px;',
		'height: ' . $sizes[$size]['h'] . 'px;',
		'line-height: ' . $sizes[$size]['h'] . 'px;',
		'font-size: ' . $sizes[$size]['h'] . 'px;',
	],
	'class' => 'pages-icon',
];

echo elgg_view('output/url', [
	'href' => $annotation->getURL(),
	'text' => elgg_view_icon('file-text-o', $icon_vars),
	'title' => $entity->title
]);
