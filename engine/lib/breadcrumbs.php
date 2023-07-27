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
 * @param bool        $link_self (deprecated) Add a link to the entity
 *
 * @return void
 */
function elgg_push_entity_breadcrumbs(\ElggEntity $entity, bool $link_self = null): void {

	elgg_push_collection_breadcrumbs($entity->type, $entity->subtype, $entity->getContainerEntity());

	if (isset($link_self)) {
		elgg_deprecated_notice('Using link_self argument is deprecated. A link to self will always be added if not on the "view" route of the entity.', '5.1');
	} else {
		$link_self = elgg_get_current_route_name() !== "view:{$entity->type}:{$entity->subtype}";
	}
	
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
		if (!$container instanceof \ElggSite && $entity_type !== 'group') {
			elgg_register_menu_item('breadcrumbs', [
				'name' => 'container',
				'text' => $container->getDisplayName(),
				'href' => $container->getURL(),
			]);
		}

		if ($friends) {
			$collection_route = "collection:{$entity_type}:{$entity_subtype}:friends";
		} elseif ($entity_type === 'group') {
			$collection_route = "collection:{$entity_type}:{$entity_subtype}:all";
		} elseif ($container instanceof \ElggUser) {
			$collection_route = "collection:{$entity_type}:{$entity_subtype}:owner";
		} elseif ($container instanceof \ElggGroup) {
			$collection_route = "collection:{$entity_type}:{$entity_subtype}:group";
		} elseif ($container instanceof \ElggSite) {
			$collection_route = "collection:{$entity_type}:{$entity_subtype}:all";
		} else {
			$collection_route = "collection:{$entity_type}:{$entity_subtype}:container";
		}
		
		if ($collection_route === elgg_get_current_route_name()) {
			return;
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
			
			if (elgg_route_exists($collection_route)) {
				elgg_register_menu_item('breadcrumbs', [
					'name' => 'collection',
					'text' => $label,
					'href' => elgg_generate_url($collection_route, $parameters),
				]);
			}
		}
		
		return;
	}

	$all_route_name = "collection:{$entity_type}:{$entity_subtype}:all";
	if (!elgg_route_exists($all_route_name) || ($all_route_name === elgg_get_current_route_name())) {
		return;
	}
	
	elgg_register_menu_item('breadcrumbs', [
		'name' => 'collection',
		'text' => elgg_echo("collection:{$entity_type}:{$entity_subtype}"),
		'href' => elgg_generate_url($all_route_name),
	]);
}
