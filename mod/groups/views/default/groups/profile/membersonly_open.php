<?php
/**
 * Message on members only, open membership group profile pages when user
 * cannot access group content
 */

elgg_deprecated_notice('The "groups/profile/membersonly_open" view has been deprecated.', '6.1');

$output = elgg_echo('groups:opengroup:membersonly');
if (elgg_is_logged_in()) {
	$output .= ' ' . elgg_echo('groups:opengroup:membersonly:join');
}

echo elgg_view_message('notice', $output, ['class' => 'mtl', 'title' => false]);
