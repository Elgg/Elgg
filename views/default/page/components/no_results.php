<?php
/**
 * No results view
 *
 * @uses $vars['no_results'] Message to display if no results (string|Closure|true)
 */

$no_results = elgg_extract('no_results', $vars);
if (empty($no_results)) {
	return;
}

if ($no_results instanceof Closure) {
	echo $no_results();
	return;
}

if ($no_results === true) {
	$no_results = elgg_echo('notfound');
}

echo elgg_format_element('p', ['class' => ['elgg-no-results']], $no_results);
