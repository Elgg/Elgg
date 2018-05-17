<?php

$messageboard = elgg_extract('result', $vars);
if (!$messageboard instanceof ElggAnnotation) {
	return;
}

echo elgg_get_excerpt($messageboard->value);

