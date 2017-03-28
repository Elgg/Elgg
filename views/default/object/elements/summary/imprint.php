<?php
/**
 * Outputs object imprint
 * @uses $vars['imprint'] Imprint
 */
$imprint = elgg_extract('imprint', $vars);
if (!isset($imprint)) {
	$imprint = elgg_view('object/elements/imprint', $vars);
}
if (!$imprint) {
	return;
}
?>
<div class="elgg-listing-summary-imprint elgg-subtext"><?= $imprint ?></div>
