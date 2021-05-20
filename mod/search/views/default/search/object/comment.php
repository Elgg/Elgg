<?php
/**
 * View for an comment entity returned in a search
 * @uses $vars['entity'] Entity returned in a search
 */
$entity = elgg_extract('entity', $vars);
if (!$entity instanceof \ElggComment) {
	return;
}

// prepare comment specific data
$container = $entity->getContainerEntity();
if ($container instanceof \ElggEntity) {
	$title = $container->getDisplayName();
	
	if (!$title) {
		$keys = [
			"item:{$container->type}:{$container->getSubtype()}",
			"item:{$container->subtype}",
			'untitled',
		];
	
		foreach ($keys as $key) {
			if (elgg_language_key_exists($key)) {
				$title = elgg_echo($key);
			}
		}
	}
		
	$entity->setVolatileData('search_matched_title', elgg_echo('search:comment_on', [$title]));
}

echo elgg_view('search/entity/default', $vars);
