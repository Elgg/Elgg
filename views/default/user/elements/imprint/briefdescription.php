<?php
/**
 * Displays the briefdescription of the user
 *
 * @uses $vars['entity']                The user to show information for
 * @uses $vars['briefdescription']      The briefdescription
 *                                      If not set, will display the briefdescription of the user
 *                                      If set to false, briefdescription string will not be rendered
 * @uses $vars['briefdescription_icon'] Icon name to be used with briefdescription info
 *                                      Set to false to not render an icon
 *                                      Default is ''
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggUser) {
	return;
}

$briefdescription = elgg_extract('briefdescription', $vars);
if (!isset($briefdescription)) {
	$briefdescription = $entity->getProfileData('briefdescription');
}
if (elgg_is_empty($briefdescription) || !is_string($briefdescription)) {
	return;
}

echo elgg_view('object/elements/imprint/element', [
	'icon_name' => elgg_extract('briefdescription_icon', $vars, ''),
	'content' => $briefdescription,
	'class' => 'elgg-listing-briefdescription',
]);
