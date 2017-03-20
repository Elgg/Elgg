<?php

/**
 * Displays information about the author and container of the post
 *
 * @uses $vars['entity']      The entity to show the byline for
 * @uses $vars['byline']      Byline
 *                            If not set, will display default author/container information
 *                            If set to false, byline will not be rendered
 * @uses $vars['show_links']  Owner and container text should show as links (default: true)
 */
$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggEntity) {
	return;
}

$show_links = elgg_extract('show_links', $vars, true);

$byline_str = elgg_extract('byline', $vars);
if ($byline_str === false) {
	return;
}

if (!isset($byline_str)) {
	$parts = [];

	$owner = $entity->getOwnerEntity();
	if ($owner instanceof ElggEntity) {
		if ($show_links) {
			$owner_text = elgg_view('output/url', [
				'href' => $owner->getURL(),
				'text' => $owner->name,
				'is_trusted' => true,
			]);
		} else {
			$owner_text = $owner->name;
		}

		$parts[] = elgg_echo('byline', [$owner_text]);
	}

	$container_entity = $entity->getContainerEntity();
	if ($container_entity instanceof ElggGroup && $container_entity->guid !== elgg_get_page_owner_guid()) {
		if ($show_links) {
			$group_text = elgg_view('output/url', [
				'href' => $container_entity->getURL(),
				'text' => $container_entity->name,
				'is_trusted' => true,
			]);
		} else {
			$group_text = $container_entity->name;
		}

		$parts[] = elgg_echo('byline:ingroup', [$group_text]);
	}

	$byline_str = implode(' ', $parts);
}

echo elgg_format_element('span', [
	'class' => 'elgg-listing-byline',
		], $byline_str);
