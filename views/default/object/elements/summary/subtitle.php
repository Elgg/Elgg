<?php
/**
 * Outputs object subtitle
 * @uses $vars['subtitle'] Subtitle
 */
$subtitle = elgg_extract('subtitle', $vars);
if (!$subtitle) {
	return;
}
?>
<div class="elgg-listing-summary-subtitle elgg-subtext"><?= $subtitle ?></div>