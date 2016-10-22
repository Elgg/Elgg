<?php

/**
 * Outputs object summary content
 *
 * @uses $vars['content'] Summary content
 * @uses $vars['entity']  Entity
 */

$entity = elgg_extract('entity', $vars);
$content = elgg_extract('content', $vars);
if (!isset($content)) {
	$content = $entity->excerpt;
	if (!isset($content)) {
		$content = elgg_get_excerpt($entity->description);
	}
}
if (!$content) {
	return;
}
?>
<div class="elgg-listing-summary-content elgg-content"><?= $content ?></div>