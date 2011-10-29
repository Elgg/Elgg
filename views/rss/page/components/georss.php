<?php
/**
 * GeoRSS view
 *
 * This implements GeoRSS-Simple
 *
 * @uses $vars['entity']
 */

$longitude = $vars['entity']->getLongitude();
$latitude = $vars['entity']->getLatitude();

if ($vars['entity'] instanceof Locatable && $longitude && $latitude) {
	echo "<georss:point>$latitude $longitude</georss:point>";
}
