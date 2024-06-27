<?php
/**
 * Elgg page header image
 */

$header_url = elgg_extract('header_url', $vars);
if ($header_url === false) {
	// we do not want a header image
	return;
}

$entity = elgg_extract('entity', $vars);
$type = 'header';
$size = 'header';

$class = ['elgg-header-image'];

if (empty($header_url)) {
	if (!$entity instanceof \ElggEntity || !$entity->hasIcon($size, $type)) {
		return;
	}
	
	$class[] = "elgg-header-image-{$entity->type}-{$entity->subtype}";
	
	$header_url = $entity->getIconUrl(['type' => $type, 'size' => $size]);
}

echo elgg_format_element('div', [
	'class' => $class,
], elgg_format_element('div', ['style' => "background-image: url({$header_url})"]));
