<?php
/**
 * Serve up html for a post
 */

$guid = (int) get_input('guid');

$parent = thewire_get_parent($guid);
if (!$parent) {
	return;
}

echo elgg_view_entity($parent);
