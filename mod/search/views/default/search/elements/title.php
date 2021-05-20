<?php
/**
 * Default view for a title used in search results
 *
 * Display largely controlled by a set of overrideable volatile data:
 *   - search_matched_title (defaults to entity->getDisplay())
 *   - search_url (defaults to entity->getURL())
 *
 * @uses $vars['entity'] Entity returned in a search
 */
$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

$title = $entity->getVolatileData('search_matched_title');
if (empty($title)) {
	$title = $entity->getDisplayName();

	if ($entity instanceof \ElggUser) {
		$title .= " (@{$entity->username})";
	}
	
	$highlighter = elgg_extract('highlighter', $vars);
	if ($highlighter instanceof \Elgg\Search\Highlighter) {
		$title = $highlighter->highlight($title, 1, 300);
	}
}

$url = $entity->getVolatileData('search_url') ?: $entity->getURL();

echo elgg_view_url($url, $title, ['class' => 'search-matched-title']);
