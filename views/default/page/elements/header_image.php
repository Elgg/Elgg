<?php
/**
 * Elgg page header image
 */

$entity = elgg_extract('entity', $vars);
$type = 'header';
$size = 'header';
$header_url = elgg_extract('header_url', $vars);
$class = ['elgg-header-image'];

if (empty($header_url)) {
	if (!$entity instanceof \ElggEntity || !$entity->hasIcon($size, $type)) {
		return;
	}
	
	$class[] = "elgg-header-image-{$entity->type}-{$entity->subtype}";
	
	$header_url = $entity->getIconUrl(['type' => $type, 'size' => $size]);
}

echo elgg_format_element('div', [
	'style' => "background-image: url({$header_url})",
	'class' => $class,
]);
