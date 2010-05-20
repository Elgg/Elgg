<?php
/**
 * 
 */

if (($vars['entity'] instanceof Locatable) &&
	($latitude = $vars['entity']->getLongitude()) && ($longitude = $vars['entity']->getLatitude())
) {
	echo "<georss:point>$latitude $longitude</georss:point>";
}