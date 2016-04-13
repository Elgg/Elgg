<?php

/**
 * Icon display
 *
 * @package ElggGroups
 * @deprecated 2.2
 */
elgg_deprecated_notice('icon.php has been depreated and should not be included. Use elgg_get_inline_url() instead.', '2.2');

$guid = get_input('group_guid');
$size = get_input('size');
if (!isset($size) || $size === '') {
   $size = 'medium';
}

elgg_entity_gatekeeper($guid, 'group');

$group = get_entity($guid);

$icon = $group->getIcon($size);
$url = elgg_get_inline_url($icon, true);
if (!$url) {
	$url = elgg_get_simplecache_url("groups/default{$size}.gif");
}

forward($url);
