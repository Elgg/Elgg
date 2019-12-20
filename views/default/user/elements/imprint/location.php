<?php
/**
 * Displays information about the location of the user
 *
 * @uses $vars['entity']        The user to show information for
 * @uses $vars['location']      Time of the post
 *                              If not set, will display the time when the entity was created (time_created attribute)
 *                              If set to false, time string will not be rendered
 * @uses $vars['location_icon'] Icon name to be used with time info
 *                              Set to false to not render an icon
 *                              Default is 'map-marker'
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggUser) {
	return;
}

$location = elgg_extract('location', $vars);
if (!isset($location)) {
	$location = $entity->getProfileData('location');
}

if (elgg_is_empty($location)) {
	return;
}

echo elgg_view('object/elements/imprint/element', [
	'icon_name' => elgg_extract('location_icon', $vars, 'map-marker'),
	'content' => $location,
	'class' => 'elgg-listing-location',
]);
