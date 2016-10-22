<?php
/**
 * Outputs object subtitle
 * 
 * @uses $vars['subtitle'] Subtitle
 */
$subtitle = elgg_extract('subtitle', $vars);
if (!$subtitle) {
	return;
}
?>
<h4 class="elgg-listing-summary-subtitle"><?= $subtitle ?></h4>