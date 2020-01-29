<?php

namespace Elgg\Menus;

use Elgg\Menu\MenuItems;

/**
 * Register menu items to the entity_navigation menu
 *
 * @since 4.0
 * @internal
 */
class EntityNavigation {

	/**
	 * Register the previous/next menu items
	 *
	 * @param \Elgg\Hook $hook 'register', 'menu:entity_navigation'
	 *
	 * @return void|MenuItems
	 */
	public static function registerPreviousNext(\Elgg\Hook $hook) {
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \ElggEntity) {
			return;
		}
		
		/* @var $return MenuItems */
		$return = $hook->getValue();
		
		$options = [
			'type' => $entity->getType(),
			'subtype' => $entity->getSubtype(),
			'container_guid' => $entity->container_guid,
			'limit' => 1,
		];
		
		$previous = self::getPreviousMenuItem($entity, $options);
		if ($previous instanceof \ElggMenuItem) {
			$return[] = $previous;
		}
		
		$next = self::getNextMenuItem($entity, $options);
		if ($next instanceof \ElggMenuItem) {
			$return[] = $next;
		}
		
		return $return;
	}
	
	/**
	 * Get a menu item to an entity before the current entity
	 *
	 * @param \ElggEntity $entity       the current entity
	 * @param array       $base_options elgg_get_entities options
	 *
	 * @return void|\ElggMenuItem
	 */
	protected static function getPreviousMenuItem(\ElggEntity $entity, array $base_options) {
		$base_options['wheres'] = [
			function (\Elgg\Database\QueryBuilder $qb, $main_alias) use ($entity) {
				return $qb->merge([
					$qb->compare("{$main_alias}.time_created", '<', $entity->time_created, ELGG_VALUE_INTEGER),
					$qb->merge([
						$qb->compare("{$main_alias}.time_created", '=', $entity->time_created, ELGG_VALUE_INTEGER),
						$qb->compare("{$main_alias}.guid", '<', $entity->guid, ELGG_VALUE_GUID),
					], 'AND'),
				], 'OR');
			},
		];
		$base_options['order_by'] = [
			new \Elgg\Database\Clauses\OrderByClause('time_created', 'DESC'),
			new \Elgg\Database\Clauses\OrderByClause('guid', 'DESC'),
		];
		
		$previous = elgg_get_entities($base_options);
		if (empty($previous)) {
			return;
		}
		
		$previous = $previous[0];
		return \ElggMenuItem::factory([
			'name' => 'previous',
			'text' => elgg_echo('previous'),
			'title' => $previous->getDisplayName(),
			'href' => $previous->getUrl(),
			'entity' => $previous,
			'icon' => 'angle-double-left',
			'link_class' => 'elgg-button elgg-button-outline',
			'priority' => 100,
		]);
	}
	
	/**
	 * Get a menu item to an entity after the current entity
	 *
	 * @param \ElggEntity $entity       the current entity
	 * @param array       $base_options elgg_get_entities options
	 *
	 * @return void|\ElggMenuItem
	 */
	protected static function getNextMenuItem(\ElggEntity $entity, array $base_options) {
		$base_options['wheres'] = [
			function (\Elgg\Database\QueryBuilder $qb, $main_alias) use ($entity) {
				return $qb->merge([
					$qb->compare("{$main_alias}.time_created", '>', $entity->time_created, ELGG_VALUE_INTEGER),
					$qb->merge([
						$qb->compare("{$main_alias}.time_created", '=', $entity->time_created, ELGG_VALUE_INTEGER),
						$qb->compare("{$main_alias}.guid", '>', $entity->guid, ELGG_VALUE_GUID),
					], 'AND'),
				], 'OR');
			},
		];
		$base_options['order_by'] = [
			new \Elgg\Database\Clauses\OrderByClause('time_created', 'ASC'),
			new \Elgg\Database\Clauses\OrderByClause('guid', 'ASC'),
		];
		
		$next = elgg_get_entities($base_options);
		if (empty($next)) {
			return;
		}
		
		$next = $next[0];
		return \ElggMenuItem::factory([
			'name' => 'next',
			'text' => elgg_echo('next'),
			'title' => $next->getDisplayName(),
			'href' => $next->getUrl(),
			'entity' => $next,
			'icon_alt' => 'angle-double-right',
			'link_class' => 'elgg-button elgg-button-outline',
			'priority' => 800,
		]);
	}
}
