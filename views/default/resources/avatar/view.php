<?php

/**
 * View an avatar
 * @deprecated 2.2
 */

elgg_deprecated_notice("/avatar/view resource view has been deprecated and will be removed. Use elgg_get_inline_url() instead.", '2.2');

// page owner library sets this based on URL
$user = elgg_get_page_owner_entity();

// Get the size
$size = strtolower(elgg_extract('size', $vars));
if (!in_array($size, array('master', 'large', 'medium', 'small', 'tiny', 'topbar'))) {
	$size = 'medium';
}

$avatar_url = false;

if ($user) {
	$filehandler = new ElggFile();
	$filehandler->owner_guid = $user->guid;
	$filehandler->setFilename("profile/{$user->guid}{$size}.jpg");
	$avatar_url = elgg_get_inline_url($filehandler);
}

if (!$avatar_url) {
	$avatar_url = elgg_get_simplecache_url("icons/user/default{$size}.gif");
}

forward($avatar_url);
