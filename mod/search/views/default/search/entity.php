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

$vars['highlighter'] = $service->getHighlighter();

$type = $entity->getType();
$subtype = $entity->getSubtype();
$search_type = elgg_extract('search_type', $params);

$views = [
	"search/{$search_type}/{$type}/{$subtype}",
	"search/{$search_type}/{$type}/default",
	"search/{$type}/{$subtype}",
	"search/{$type}/default",
	'search/entity/default',
];

foreach ($views as $view) {
	if (elgg_view_exists($view)) {
		echo elgg_view($view, $vars);
		
		return;
	}
}
