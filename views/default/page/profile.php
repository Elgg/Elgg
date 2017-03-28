<?php

$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	elgg_log("\$vars['entity'] is required in page/profile view", 'ERROR');
	return;
}

echo elgg_view('page/default', $vars);