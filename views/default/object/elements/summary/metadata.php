<?php

/**
 * Outputs object metadata
 * @uses $vars['metadata'] Metadata/menu
 */

$metadata = elgg_extract('metadata', $vars);
if (!$metadata) {
	return;
}
echo $metadata;