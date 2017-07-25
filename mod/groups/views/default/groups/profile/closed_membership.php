<?php
/**
 * Message for non-members on closed membership group profile pages.
 *
 * @package ElggGroups
 */

$output = elgg_echo('groups:closedgroup');
if (elgg_is_logged_in()) {
	$output .= ' ' . elgg_echo('groups:closedgroup:request');
}

echo "<p class='mtm'>$output</p>";
