<?php
/**
 * Show river activity from friends
 */

use Elgg\Exceptions\Http\EntityNotFoundException;

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner instanceof \ElggUser) {
	throw new EntityNotFoundException(elgg_echo('river:subject:invalid_subject'));
}

elgg_push_breadcrumb($page_owner->getDisplayName(), $page_owner->getURL());
elgg_push_breadcrumb(elgg_echo('river:friends'), elgg_generate_url('collection:river:friends', ['username' => $page_owner->username]));

// get filter options
$type = preg_replace('[\W]', '', get_input('type', 'all'));
$subtype = preg_replace('[\W]', '', get_input('subtype', ''));

// build page content
$content = elgg_view('river/listing/friends', [
	'entity' => $page_owner,
	'entity_type' => $type,
	'entity_subtype' => $subtype,
	'show_filter' => true,
]);

// draw page
echo elgg_view_page(elgg_echo('river:friends'), [
	'content' =>  $content,
	'sidebar' => elgg_view('river/sidebar'),
	'filter_value' => $page_owner->guid === elgg_get_logged_in_user_guid() ? 'friends' : 'none',
	'class' => 'elgg-river-layout',
]);
