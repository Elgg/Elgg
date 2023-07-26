<?php
/**
 * Breadcrumbs
 */

/**
 * Adds a breadcrumb to the breadcrumbs stack.
 *
 * @param string       $text The title to display. During rendering this is HTML encoded.
 * @param false|string $href Optional. The href for the title. During rendering links are
 *                           normalized via elgg_normalize_url().
 *
 * @return void
 * @since 1.8.0
 */
function elgg_push_breadcrumb(string $text, string|false $href = false): void {
	static $i = 0;
	$i++;
	elgg_register_menu_item('breadcrumbs', [
		'name' => "breadcrumb-{$i}",
		'text' => $text,
		'href' => $href,
	]);
}

/**
 * Resolves and pushes entity breadcrumbs based on named routes
 *
 * @param \ElggEntity $entity    Entity
 * @param bool        $link_self Add a link to the entity
 *
 * @return void
 */
function elgg_push_entity_breadcrumbs(\ElggEntity $entity, bool $link_self = true): void {

	elgg_push_collection_breadcrumbs($entity->type, $entity->subtype, $entity->getContainerEntity());

	if ($link_self) {
		elgg_register_menu_item('breadcrumbs', [
			'name' => 'entity',
			'text' => $entity->getDisplayName(),
			'href' => $entity->getURL(),
		]);
	}
}

/**
 * Resolves and pushes collection breadcrumbs for a container
 *
 * @param string          $entity_type    Entity type in the collection
 * @param string          $entity_subtype Entity subtype in the collection
 * @param ElggEntity|null $container      Container/page owner entity
 * @param bool            $friends        Collection belongs to container's friends?
 *
 * @return void
 */
function elgg_push_collection_breadcrumbs(string $entity_type, string $entity_subtype, \ElggEntity $container = null, bool $friends = false): void {

	if ($container) {
		if (!$container instanceof \ElggSite) {
			elgg_register_menu_item('breadcrumbs', [
				'name' => 'container',
				'text' => $container->getDisplayName(),
				'href' => $container->getURL(),
			]);
		}

		if ($friends) {
			$collection_route = "collection:{$entity_type}:{$entity_subtype}:friends";
		} else if ($container instanceof ElggUser) {
			$collection_route = "collection:{$entity_type}:{$entity_subtype}:owner";
		} else if ($container instanceof ElggGroup) {
			$collection_route = "collection:{$entity_type}:{$entity_subtype}:group";
		} else if ($container instanceof ElggSite) {
			$collection_route = "collection:{$entity_type}:{$entity_subtype}:all";
		} else {
			$collection_route = "collection:{$entity_type}:{$entity_subtype}:container";
		}

		$parameters = _elgg_services()->routes->resolveRouteParameters($collection_route, $container);
		if ($parameters !== false) {
			$label = elgg_echo("collection:{$entity_type}:{$entity_subtype}");
			if ($friends) {
				if (elgg_language_key_exists("collection:{$entity_type}:{$entity_subtype}:friends")) {
					$label = elgg_echo("collection:{$entity_type}:{$entity_subtype}:friends");
				} else {
					$label = elgg_echo('collection:friends', [$label]);
				}
			}
			
			elgg_register_menu_item('breadcrumbs', [
				'name' => 'collection',
				'text' => $label,
				'href' => elgg_generate_url($collection_route, $parameters),
			]);
		}
	} else {
		elgg_register_menu_item('breadcrumbs', [
			'name' => 'collection',
			'text' => elgg_echo("collection:{$entity_type}:{$entity_subtype}"),
			'href' => elgg_generate_url("collection:{$entity_type}:{$entity_subtype}:all"),
		]);
	}
}
