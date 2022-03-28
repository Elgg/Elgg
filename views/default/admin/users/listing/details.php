<?php
/**
 * Ajax view to list user details on admin listings
 *
 * @uses $vars['entity'] The user entity to show details for
 * @uses $vars['guid']   The user GUID to show details for
 */

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggUser) {
	// ajax loaded couldn't autoload the entity from the 'guid' input
	$entity = elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() use ($vars) {
		return get_user((int) elgg_extract('guid', $vars));
	});
	
	// store in $vars for later views
	$vars['entity'] = $entity;
}

if (!$entity instanceof \ElggUser) {
	return;
}

// output
echo elgg_view('page/components/tabs', [
	'tabs' => [
		// user attributes
		[
			'text' => elgg_echo('admin:users:details:attributes'),
			'content' => elgg_view('admin/users/listing/attributes', $vars),
			'selected' => true,
		],
		// content statistics
		[
			'text' => elgg_echo('admin:users:details:statistics'),
			'content' => elgg_view('core/settings/statistics/numentities', $vars),
		],
		// profile data
		[
			'text' => elgg_echo('admin:users:details:profile'),
			'content' => elgg_view('admin/users/listing/profile', $vars),
		],
	],
]);
