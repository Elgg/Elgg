<?php
/**
 * Outputs object categories
 * @uses $vars['categories'] Imprint
 */
$categories = elgg_extract('categories', $vars);
if (!isset($categories)) {
	$categories = elgg_view('output/categories', $vars);
}
if (!$categories) {
	return;
}
?>
<div class="elgg-listing-summary-categories elgg-subtext"><?= $categories ?></div>
