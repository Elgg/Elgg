<?php
/**
 * Message for non-members on closed membership group profile pages
 */

elgg_deprecated_notice('The "groups/profile/closed_membership" view has been deprecated.', '6.1');

$output = elgg_echo('groups:closedgroup');
if (elgg_is_logged_in()) {
	$output .= ' ' . elgg_echo('groups:closedgroup:request');
}

echo elgg_view_message('notice', $output, ['class' => 'mtl', 'title' => false]);
