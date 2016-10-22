<?php

/**
 * Outputs object metadata
 */

$by_line = elgg_extract('by_line', $vars);
if (!isset($by_line)) {
	$by_line = elgg_view('page/elements/by_line', $vars);
}

$responses_link = elgg_extract('responses_link', $vars);

$access = elgg_extract('access', $vars);
if (!isset($access)) {
	$access = elgg_view('output/access', $vars);
}

$status = elgg_extract('status', $vars);
$metadata[] = implode(' | ', array_filter([$by_line, $responses_link]));
$metadata[] = implode(' | ', array_filter([$status, $access]));

$metadata = implode('<br />', array_filter($metadata));
if (!$metadata) {
	return;
}
?>
<div class="elgg-listing-summary-metadata elgg-subtext"><?= $metadata ?></div>