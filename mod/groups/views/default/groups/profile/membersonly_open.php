<?php
/**
 * Message on members only, open membership group profile pages when user
 * cannot access group content
 */

$output = elgg_echo('groups:opengroup:membersonly');
if (elgg_is_logged_in()) {
	$output .= ' ' . elgg_echo('groups:opengroup:membersonly:join');
}

echo elgg_view_message('notice', $output, ['class' => 'mtl']);
