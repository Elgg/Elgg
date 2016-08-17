<?php

/**
 * Outputs object summary content
 * @uses $vars['content'] Summary content
 */

$content = elgg_extract('content', $vars);
if (!$content) {
	return;
}
?>
<div class="elgg-listing-summary-content elgg-content"><?= $content ?></div>