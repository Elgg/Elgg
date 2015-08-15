<?php
/**
 * Elgg relationships.
 *
 * @package    Elgg.Core
 * @subpackage DataModel.Relationship
 */

/**
 * Convert a database row to a new \ElggRelationship
 *
 * @param \stdClass $row Database row from the relationship table
 *
 * @return \ElggRelationship|false
 * @access private
 */
function row_to_elggrelationship($row) {
	if ($row instanceof \stdClass) {
		return new \ElggRelationship($row);
	}

	return false;
}

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
 * Get a database row from the relationship table
 *
 * @param int $id The relationship ID
 *
 * @return \stdClass|false False if no row found
 * @access private
 */
function _elgg_get_relationship_row($id) {
	return _elgg_services()->relationshipsTable->getRow($id);
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
 * @throws InvalidArgumentException
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
 * Return entities matching a given query joining against a relationship.
 *
 * By default the function finds relationship targets. E.g.:
 *
 *   // find groups with a particular member:
 *   $options = [
 *       'relationship' => 'member',
 *       'relationship_guid' => $member->guid,
 *   ];
 *
 *   // find people the user has friended
 *   $options = [
 *       'relationship' => 'friend',
 *       'relationship_guid' => $user->guid,
 *   ];
 *
 *   // find stuff created by friends (not in groups)
 *   $options = [
 *       'relationship' => 'friend',
 *       'relationship_guid' => $user->guid,
 *       'relationship_join_on' => 'container_guid',
 *   ];
 *
 * To find relationship subjects, set "inverse_relationship" to true. E.g.:
 *
 *   // find members of a particular group
 *   $options = [
 *       'relationship' => 'member',
 *       'relationship_guid' => $group->guid,
 *       'inverse_relationship' => true,
 *   ];
 *
 *   // find users who have friended the current user
 *   $options = [
 *       'relationship' => 'friend',
 *       'relationship_guid' => $user->guid,
 *       'inverse_relationship' => true,
 *   ];
 *
 * @note You may want to specify "type" because relationship types might be used for other entities.
 *
 * This also accepts all options available to elgg_get_entities() and elgg_get_entities_from_metadata().
 *
 * To ask for entities that do not have a particular relationship to an entity,
 * use a custom where clause like the following:
 *
 * 	$options['wheres'][] = "NOT EXISTS (
 *			SELECT 1 FROM {$db_prefix}entity_relationships
 *				WHERE guid_one = e.guid
 *				AND relationship = '$relationship'
 *		)";
 *
 * @see elgg_get_entities
 * @see elgg_get_entities_from_metadata
 *
 * @param array $options Array in format:
 *
 *  relationship => null|STR Type of the relationship. E.g. "member"
 *
 *  relationship_guid => null|INT GUID of the subject of the relationship, unless "inverse_relationship" is set
 *                                to true, in which case this will specify the target.
 *
 *  inverse_relationship => false|BOOL Are we searching for relationship subjects? By default, the query finds
 *                                     targets of relationships.
 * 
 *  relationship_join_on => null|STR How the entities relate: guid (default), container_guid, or owner_guid
 *                                   Examples using the relationship 'friend':
 *                                   1. use 'guid' if you want the user's friends
 *                                   2. use 'owner_guid' if you want the entities the user's friends own
 *                                      (including in groups)
 *                                   3. use 'container_guid' if you want the entities in the user's personal
 *                                      space (non-group)
 *                          
 * 	relationship_created_time_lower => null|INT Relationship created time lower boundary in epoch time
 *
 * 	relationship_created_time_upper => null|INT Relationship created time upper boundary in epoch time
 *
 * @return \ElggEntity[]|mixed If count, int. If not count, array. false on errors.
 * @since 1.7.0
 */
function elgg_get_entities_from_relationship($options) {
	return _elgg_services()->relationshipsTable->getEntities($options);
}

/**
 * Returns SQL appropriate for relationship joins and wheres
 *
 * @todo add support for multiple relationships and guids.
 *
 * @param string $column               Column name the GUID should be checked against.
 *                                     Provide in table.column format.
 * @param string $relationship         Type of the relationship
 * @param int    $relationship_guid    Entity GUID to check
 * @param bool   $inverse_relationship Is $relationship_guid the target of the relationship?
 *
 * @return mixed
 * @since 1.7.0
 * @access private
 */
function elgg_get_entity_relationship_where_sql($column, $relationship = null,
		$relationship_guid = null, $inverse_relationship = false) {
	return _elgg_services()->relationshipsTable->getEntityRelationshipWhereSql(
		$column, $relationship, $relationship_guid, $inverse_relationship);
}

/**
 * Returns a viewable list of entities by relationship
 *
 * @param array $options Options array for retrieval of entities
 *
 * @see elgg_list_entities()
 * @see elgg_get_entities_from_relationship()
 *
 * @return string The viewable list of entities
 */
function elgg_list_entities_from_relationship(array $options = array()) {
	return elgg_list_entities($options, 'elgg_get_entities_from_relationship');
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
function elgg_get_entities_from_relationship_count(array $options = array()) {
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
