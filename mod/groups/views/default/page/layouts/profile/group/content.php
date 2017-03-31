<?php

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggGroup) {
	return;
}

echo elgg_view('groups/profile/layout', $vars);
