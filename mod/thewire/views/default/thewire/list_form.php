<?php
/**
 * Prepend the listing of TheWire with an add form
 *
 * @uses $vars['page']     the current listing page (all, owner, friends, etc.)
 * @uses $vars['add_form'] should the form be added
 */

$page = elgg_extract('page', $vars);
$user = elgg_get_logged_in_user_entity();

$add_form = false;
if ($page === 'all') {
	$add_form = ($user instanceof \ElggUser);
} elseif (in_array($page, ['friends', 'owner'])) {
	$add_form = $user?->guid === elgg_get_page_owner_guid();
}

$add_form = (bool) elgg_extract('add_form', $vars, $add_form);
if (!$add_form) {
	return;
}

echo elgg_view_form('thewire/add');
