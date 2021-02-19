<?php
/**
 * Breadcrumbs
 * Elgg uses a breadcrumb stack. The page handlers (controllers in MVC terms)
 * push the breadcrumb links onto the stack. @see elgg_push_breadcrumb()
 */

/**
 * Adds a breadcrumb to the breadcrumbs stack.
 *
 * See elgg_get_breadcrumbs() and the navigation/breadcrumbs view.
 *
 * @param string       $text The title to display. During rendering this is HTML encoded.
 * @param false|string $href Optional. The href for the title. During rendering links are
 *                           normalized via elgg_normalize_url().
 *
 * @return void
 * @since 1.8.0
 * @see elgg_get_breadcrumbs()
 */
function elgg_push_breadcrumb($text, $href = false): void {
	$breadcrumbs = (array) _elgg_services()->config->breadcrumbs;
	
	$breadcrumbs[] = [
		'text' => $text,
		'href' => $href,
	];
	
	elgg_set_config('breadcrumbs', $breadcrumbs);
}

/**
 * Removes last breadcrumb entry.
 *
 * @return array popped breadcrumb array or empty array
 * @since 1.8.0
 */
function elgg_pop_breadcrumb(): array {
	$breadcrumbs = (array) _elgg_services()->config->breadcrumbs;

	if (empty($breadcrumbs)) {
		return [];
	}

	$popped = array_pop($breadcrumbs);
	elgg_set_config('breadcrumbs', $breadcrumbs);

	return $popped;
}

/**
 * Returns all breadcrumbs as an array
 * <code>
 * [
 *    [
 *       'text' => 'Breadcrumb title',
 *       'href' => '/path/to/page',
 *    ]
 * ]
 * </code>
 *
 * Breadcrumbs are filtered through the plugin hook [prepare, breadcrumbs] before
 * being returned.
 *
 * @param array $breadcrumbs An array of breadcrumbs
 *                           If set, will override breadcrumbs in the stack
 * @return array
 * @since 1.8.0
 * @see \Elgg\Page\PrepareBreadcrumbsHandler::class
 */
function elgg_get_breadcrumbs(array $breadcrumbs = null): array {
	if (!isset($breadcrumbs)) {
		// if no crumbs set, still allow hook to populate it
		$breadcrumbs = (array) _elgg_services()->config->breadcrumbs;
	}
	
	$params = [
		'breadcrumbs' => $breadcrumbs,
	];

	$params['identifier'] = _elgg_services()->request->getFirstUrlSegment();
	$params['segments'] = _elgg_services()->request->getUrlSegments();
	array_shift($params['segments']);

	$breadcrumbs = elgg_trigger_plugin_hook('prepare', 'breadcrumbs', $params, $breadcrumbs);
	if (!is_array($breadcrumbs)) {
		_elgg_services()->logger->error('"prepare, breadcrumbs" hook must return an array of breadcrumbs');
		return [];
	}
	
	foreach ($breadcrumbs as $key => $breadcrumb) {
		// adds name for usage in menu items
		if (!isset($breadcrumb['name'])) {
			$breadcrumbs[$key]['name'] = $key;
		}
	}

	return $breadcrumbs;
}

/**
 * Resolves and pushes entity breadcrumbs based on named routes
 *
 * @param ElggEntity $entity    Entity
 * @param bool       $link_self Use entity link in the last crumb
 *
 * @return void
 */
function elgg_push_entity_breadcrumbs(ElggEntity $entity, $link_self = true): void {

	$container = $entity->getContainerEntity() ? : null;
	elgg_push_collection_breadcrumbs($entity->type, $entity->subtype, $container);

	$entity_url = $link_self ? $entity->getURL() : false;
	elgg_push_breadcrumb($entity->getDisplayName(), $entity_url);
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
function elgg_push_collection_breadcrumbs($entity_type, $entity_subtype, ElggEntity $container = null, $friends = false): void {

	if ($container) {
		if (!$container instanceof \ElggSite) {
			elgg_push_breadcrumb($container->getDisplayName(), $container->getURL());
		}

		if ($friends) {
			$collection_route = "collection:$entity_type:$entity_subtype:friends";
		} else if ($container instanceof ElggUser) {
			$collection_route = "collection:$entity_type:$entity_subtype:owner";
		} else if ($container instanceof ElggGroup) {
			$collection_route = "collection:$entity_type:$entity_subtype:group";
		} else if ($container instanceof ElggSite) {
			$collection_route = "collection:$entity_type:$entity_subtype:all";
		} else {
			$collection_route = "collection:$entity_type:$entity_subtype:container";
		}

		$parameters = _elgg_services()->routes->resolveRouteParameters($collection_route, $container);
		if ($parameters !== false) {
			$label = elgg_echo("collection:$entity_type:$entity_subtype");
			if ($friends) {
				if (elgg_language_key_exists("collection:$entity_type:$entity_subtype:friends")) {
					$label = elgg_echo("collection:$entity_type:$entity_subtype:friends");
				} else {
					$label = elgg_echo('collection:friends', [$label]);
				}
			}
			$collection_url = elgg_generate_url($collection_route, $parameters);
			elgg_push_breadcrumb($label, $collection_url);
		}
	} else {
		$label = elgg_echo("collection:$entity_type:$entity_subtype");
		$collection_url = elgg_generate_url("collection:$entity_type:$entity_subtype:all");
		elgg_push_breadcrumb($label, $collection_url);
	}
}
