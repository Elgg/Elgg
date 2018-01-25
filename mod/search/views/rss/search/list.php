<?php
/**
 * List a section of search results for RSS feeds.
 *
 * @uses $vars['results']
 * @uses $vars['params']
 */

$results = elgg_extract('results', $vars);
$entities = elgg_extract('entities', $results);

if (empty($entities)) {
	return;
}

$params = elgg_extract('params', $vars);
$service = new \Elgg\Search\Search($params);

foreach ($entities as $entity) {
	if ($view = $service->getSearchView()) {
		echo elgg_view($view, [
			'entity' => $entity,
			'params' => $service->getParams(),
			'results' => $results,
		]);
	}
}
