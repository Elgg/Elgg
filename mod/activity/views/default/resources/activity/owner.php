<?php
/**
 * Show river activity from user
 */

use Elgg\Exceptions\Http\EntityNotFoundException;

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner instanceof \ElggUser) {
	throw new EntityNotFoundException(elgg_echo('river:subject:invalid_subject'));
}

// build page content
if ($page_owner->guid === elgg_get_logged_in_user_guid()) {
	$title = elgg_echo('river:mine');
	$page_filter = 'mine';
} else {
	$title = elgg_echo('river:owner', [$page_owner->getDisplayName()]);
	$page_filter = 'subject';
}

elgg_push_breadcrumb($page_owner->getDisplayName(), $page_owner->getURL());
elgg_push_breadcrumb($title, elgg_generate_url('collection:river:owner', ['username' => $page_owner->username]));

// get filter options
$type = preg_replace('[\W]', '', get_input('type', 'all'));
$subtype = preg_replace('[\W]', '', get_input('subtype', ''));


$content = elgg_view('river/listing/owner', [
	'entity' => $page_owner,
	'entity_type' => $type,
	'entity_subtype' => $subtype,
	'show_filter' => true,
]);

// draw page
echo elgg_view_page($title, [
	'content' =>  $content,
	'sidebar' => elgg_view('river/sidebar'),
	'filter_value' => $page_filter,
	'class' => 'elgg-river-layout',
]);
