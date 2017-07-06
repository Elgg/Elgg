<?php
/**
 * No results view
 *
 * @uses $vars['no_results'] Message to display if no results (string|Closure)
 */
$no_results = elgg_extract('no_results', $vars);
if (empty($no_results)) {
	return;
}

if ($no_results instanceof Closure) {
	echo $no_results();
	return;
}

echo "<p class='elgg-no-results'>$no_results</p>";
