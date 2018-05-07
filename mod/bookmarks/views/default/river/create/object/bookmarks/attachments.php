<?php

$object = elgg_extract('object', $vars);
if (!$object instanceof ElggBookmark) {
	return;
}

echo elgg_view('output/url', [
	'href' => $object->address,
]);