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
 * @return \ElggRelationship|false False if not found
 */
function get_relationship($id) {
	return _elgg_services()->relationshipsTable->get($id);
}

/**
 * Delete a relationship by its ID
 *
 * @param int $id The relationship ID
 *
 * @return bool
 */
function delete_relationship($id) {
	return _elgg_services()->relationshipsTable->delete($id);

}

/**
 * Create a relationship between two entities. E.g. friendship, group membership, site membership.
 *
 * This function lets you make the statement "$guid_one is a $relationship of $guid_two". In the statement,
 * $guid_one is the subject of the relationship, $guid_two is the target, and $relationship is the type.
 *
 * @param int    $guid_one     GUID of the subject entity of the relationship
 * @param string $relationship Type of the relationship
 * @param int    $guid_two     GUID of the target entity of the relationship
 *
 * @return bool
 * @throws \Elgg\Exceptions\InvalidArgumentException
 */
function add_entity_relationship($guid_one, $relationship, $guid_two) {
	return _elgg_services()->relationshipsTable->add($guid_one, $relationship, $guid_two);
}

/**
 * Check if a relationship exists between two entities. If so, the relationship object is returned.
 *
 * This function lets you ask "Is $guid_one a $relationship of $guid_two?"
 *
 * @param int    $guid_one     GUID of the subject entity of the relationship
 * @param string $relationship Type of the relationship
 * @param int    $guid_two     GUID of the target entity of the relationship
 *
 * @return \ElggRelationship|false Depending on success
 */
function check_entity_relationship($guid_one, $relationship, $guid_two) {
	return _elgg_services()->relationshipsTable->check($guid_one, $relationship, $guid_two);
}

/**
 * Delete a relationship between two entities.
 *
 * This function lets you say "$guid_one is no longer a $relationship of $guid_two."
 *
 * @param int    $guid_one     GUID of the subject entity of the relationship
 * @param string $relationship Type of the relationship
 * @param int    $guid_two     GUID of the target entity of the relationship
 *
 * @return bool
 */
function remove_entity_relationship($guid_one, $relationship, $guid_two) {
	return _elgg_services()->relationshipsTable->remove($guid_one, $relationship, $guid_two);
}

/**
 * Removes all relationships originating from a particular entity
 *
 * @param int    $guid                 GUID of the subject or target entity (see $inverse)
 * @param string $relationship         Type of the relationship (optional, default is all relationships)
 * @param bool   $inverse_relationship Is $guid the target of the deleted relationships? By default, $guid is the
 *                                     subject of the relationships.
 * @param string $type                 The type of entity related to $guid (defaults to all)
 *
 * @return true
 */
function remove_entity_relationships($guid, $relationship = "", $inverse_relationship = false, $type = '') {
	return _elgg_services()->relationshipsTable->removeAll($guid, $relationship, $inverse_relationship, $type);
}

/**
 * Get all the relationships for a given GUID.
 *
 * @param int  $guid                 GUID of the subject or target entity (see $inverse)
 * @param bool $inverse_relationship Is $guid the target of the relationships? By default $guid is
 *                                   the subject of the relationships.
 *
 * @return \ElggRelationship[]
 */
function get_entity_relationships($guid, $inverse_relationship = false) {
	return _elgg_services()->relationshipsTable->getAll($guid, $inverse_relationship);
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
		'limit' => 25,
		'offset' => (int) max(get_input('reloff', 0), 0),
	];
	
	$options = array_merge($defaults, $options);
	
	return elgg_list_entities($options, 'elgg_get_relationships', 'elgg_view_relationship_list');
}
