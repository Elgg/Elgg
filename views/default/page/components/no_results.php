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
	$no_results = call_user_func(function() use ($vars) {
		// try for an entity listing
		$type = (string) elgg_extract('type', $vars);
		$subtype = (string) elgg_extract('subtype', $vars);
		if (elgg_language_key_exists("list:{$type}:{$subtype}:no_results")) {
			return elgg_echo("list:{$type}:{$subtype}:no_results");
		}
		
		// try for an annotation listing
		$annotation_name = (string) elgg_extract('annotation_name', $vars);
		if (elgg_language_key_exists("list:annotation:{$annotation_name}:no_results")) {
			return elgg_echo("list:annotation:{$annotation_name}:no_results");
		}
		
		return elgg_echo('notfound');
	});
}

echo elgg_format_element('p', ['class' => ['elgg-no-results']], $no_results);
