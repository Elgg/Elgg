<?php
/**
 * Outputs object tags
 *
 * @uses $vars['tags']   Tags
 * @uses $vars['entity'] If tags are empty
 */
$entity = elgg_extract('entity', $vars);
$tags = elgg_extract('tags', $vars);
if (!isset($tags)) {
	$tags = elgg_view('output/tags', [
		'entity' => $entity,
	]);
}
if (!$tags) {
	return;
}
?>
<div class="elgg-listing-summary-tags"><?= $tags ?></div>