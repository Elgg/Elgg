<?php
/**
 * GeoRSS view
 *
 * This implements GeoRSS-Simple
 *
 * @uses $vars['entity']
 */

$longitude = elgg_extract('entity', $vars)->getLongitude();
$latitude = elgg_extract('entity', $vars)->getLatitude();

if ($vars['entity'] instanceof Locatable && $longitude && $latitude) {
	echo "<georss:point>$latitude $longitude</georss:point>";
}
