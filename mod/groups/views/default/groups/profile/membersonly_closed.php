<?php
/**
 * Message on members only, closed membership group profile pages when user
 * cannot access group content
 */

$output = elgg_echo('groups:closedgroup:membersonly');
if (elgg_is_logged_in()) {
	$output .= ' ' . elgg_echo('groups:closedgroup:request');
}

echo elgg_view_message('notice', $output, ['class' => 'mtl']);
