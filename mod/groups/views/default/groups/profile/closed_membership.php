<?php
/**
 * Message for non-members on closed membership group profile pages
 */

$output = elgg_echo('groups:closedgroup');
if (elgg_is_logged_in()) {
	$output .= ' ' . elgg_echo('groups:closedgroup:request');
}

echo elgg_view_message('notice', $output, ['class' => 'mtl']);
