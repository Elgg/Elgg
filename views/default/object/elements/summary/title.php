<?php

/**
 * Outputs object title
 * @uses $vars['title'] Title
 */

$title = elgg_extract('title', $vars);
if (!$title) {
	return;
}
?>
<h3 class="elgg-listing-summary-title"><?= $title ?></h3>