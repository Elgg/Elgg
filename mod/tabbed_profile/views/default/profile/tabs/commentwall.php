<?php
/**
 * Elgg profile comment wall
 */

$user = elgg_get_page_owner();
$comments = $user->getAnnotations('commentwall', 200, 0, 'desc');

if (isloggedin()) {
	echo elgg_view("profile/commentwall/commentwalladd");
}

echo elgg_view("profile/commentwall/commentwall", array('annotation' => $comments));
