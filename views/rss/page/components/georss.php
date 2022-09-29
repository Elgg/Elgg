<?php
/**
 * GeoRSS view
 *
 * This implements GeoRSS-Simple
 *
 * @uses $vars['entity']
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggEntity) {
	return;
}

$longitude = $entity->getLongitude();
$latitude = $entity->getLatitude();

if ($longitude && $latitude) {
	echo elgg_format_element('georss:point', [], "{$latitude} {$longitude}");
}
