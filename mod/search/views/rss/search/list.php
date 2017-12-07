<?php
/**
 * List a section of search results for RSS feeds.
 *
 * @uses $vars['results']
 * @uses $vars['params']
 */

$entities = $vars['results']['entities'];

if (empty($entities)) {
	return;
}

$params = elgg_extract('params', $vars);
$service = new \Elgg\Search\Search($params);

foreach ($entities as $entity) {
	if ($view = $service->getSearchView()) {
		$body .= elgg_view($view, [
			'entity' => $entity,
			'params' => $service->getParams(),
			'results' => $vars['results']
		]);
	}
}

echo $body;
