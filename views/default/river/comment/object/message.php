<?php

/**
 * Comment message
 *
 * @uses $vars['result'] River result object
 */

$result = elgg_extract('result', $vars);
if (!$result instanceof ElggComment) {
	return;
}

if (!$result->description) {
	return;
}

echo $result->getExcerpt();