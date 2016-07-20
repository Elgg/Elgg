<?php

/**
 * Outputs object full view
 * @uses $vars['body'] Body
 */

$body = elgg_extract('body', $vars);
if (!$body) {
	return;
}

echo $body;