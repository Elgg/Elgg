<?php

/**
 * Default view for an entity returned in a search
 *
 * @uses $vars['entity'] Entity returned in a search
 */
$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

$params = elgg_extract('params', $vars, []);

$service = new \Elgg\Search\Search($params);

$service->prepareEntity($entity);

$view = $service->getSearchView($entity);

if ($view && $view != 'search/entity' && elgg_view_exists($view)) {
	$vars['entity'] = $entity;
	echo elgg_view($view, $vars);

	return;
}

echo elgg_view('search/entity/default', $vars);
