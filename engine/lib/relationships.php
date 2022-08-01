<?php
/**
 * Elgg relationships.
 */

use Elgg\Database\Relationships;

/**
 * Get a relationship by its ID
 *
 * @param int $id The relationship ID
 *
 * @return \ElggRelationship|null
 * @since 4.3
 */
function elgg_get_relationship(int $id): ?\ElggRelationship {
	return _elgg_services()->relationshipsTable->get($id);
}

/**
 * Gets the number of entities by a the number of entities related to them in a particular way.
 * This is a good way to get out the users with the most friends, or the groups with the
 * most members.
 *
 * @param array $options An options array compatible with elgg_get_entities_from_relationship()
 *
 * @return \ElggEntity[]|int|boolean If count, int. If not count, array. false on errors.
 * @since 1.8.0
 */
function elgg_get_entities_from_relationship_count(array $options = []) {
	return _elgg_services()->relationshipsTable->getEntitiesFromCount($options);
}

/**
 * Returns a list of entities by relationship count
 *
 * @see elgg_get_entities_from_relationship_count()
 *
 * @param array $options Options array
 *
 * @return string
 * @since 1.8.0
 */
function elgg_list_entities_from_relationship_count($options) {
	return elgg_list_entities($options, 'elgg_get_entities_from_relationship_count');
}

/**
 * Fetch relationships or perform a calculation on them
 *
 * Accepts all options supported by {@link elgg_get_entities()}
 *
 * The default 'order_by' is 'er.time_created, er.id' DESC
 *
 * @param array $options Options
 *
 * @return \ElggRelationship[]|mixed
 *
 * @see   elgg_get_entities()
 * @since 3.2.0
 */
function elgg_get_relationships(array $options = []) {
	return Relationships::find($options);
}

/**
 * Returns a rendered list of relationships with pagination.
 *
 * @param array $options Relationship getter and display options.
 *                       {@link elgg_get_relationships()} and {@link elgg_list_entities()}.
 *
 * @return string The list of relationships
 * @since 3.2.0
 */
function elgg_list_relationships($options) {
	$defaults = [
		'limit' => (int) max(get_input('limit', max(25, _elgg_services()->config->default_limit)), 0),
		'offset' => (int) max(get_input('reloff', 0), 0),
		'sort_by' => get_input('sort_by', []),
	];
	
	$options = array_merge($defaults, $options);
	
	return elgg_list_entities($options, 'elgg_get_relationships', 'elgg_view_relationship_list');
}
