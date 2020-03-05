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
	echo "<georss:point>$latitude $longitude</georss:point>";
}
