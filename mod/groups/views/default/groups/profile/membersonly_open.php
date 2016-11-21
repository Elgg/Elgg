<?php
/**
 * Message on members only, open membership group profile pages when user
 * cannot access group content.
 *
 * @package ElggGroups
 */

$output = elgg_echo('groups:opengroup:membersonly');
if (elgg_is_logged_in()) {
	$output .= ' ' . elgg_echo('groups:opengroup:membersonly:join');
}
echo "<p class='mtm'>$output</p>";
