<?php

/**
 * Main activity stream list page
 */
$page_type = preg_replace('[\W]', '', elgg_extract('page_type', $vars, 'all'));
$type = preg_replace('[\W]', '', get_input('type', 'all'));
$subtype = preg_replace('[\W]', '', get_input('subtype', ''));

switch ($page_type) {
	case 'mine':
		$page_type = 'owner';
		$subject = elgg_get_logged_in_user_entity();
		break;
	case 'owner':
		$subject_username = elgg_extract('subject_username', $vars, '');
		$subject = get_user_by_username($subject_username);
		if (!$subject) {
			register_error(elgg_echo('river:subject:invalid_subject'));
			forward('');
		}
		break;
	case 'friends':
		if (!elgg_is_active_plugin('friends')) {
			forward('', '404');
		}

		$subject = elgg_get_logged_in_user_entity();
		break;

	default :
		$page_type = 'all';
		$subject = null;
		break;
}

$listing = [
	'identifier' => 'activity',
	'type' => $page_type,
	'target' => $subject,
	'entity_type' => false,
	'entity_subtype' => false,
];

echo elgg_view_listing_page($listing, [
	'sidebar' => elgg_view('core/river/sidebar'),
	'class' => 'elgg-river-layout',
	'river_type' => $type,
	'river_subtype' => $subtype,
]);
