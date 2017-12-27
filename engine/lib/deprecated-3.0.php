<?php

use Elgg\Database\Entities;

/**
 * Removes a config setting.
 *
 * @param string $name The name of the field.
 *
 * @return bool Success or failure
 *
 * @see get_config()
 * @see set_config()
 *
 * @deprecated Use elgg_remove_config()
 */
function unset_config($name) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use elgg_remove_config().', '3.0');
	return elgg_remove_config($name);
}

/**
 * Add or update a config setting.
 *
 * Plugin authors should use elgg_set_config().
 *
 * If the config name already exists, it will be updated to the new value.
 *
 * @param string $name      The name of the configuration value
 * @param mixed  $value     Its value
 *
 * @return bool
 * @see unset_config()
 * @see get_config()
 * @access private
 *
 * @deprecated Use elgg_save_config()
 */
function set_config($name, $value) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use elgg_save_config().', '3.0');
	return elgg_save_config($name, $value);
}

/**
 * Gets a configuration value
 *
 * Plugin authors should use elgg_get_config().
 *
 * @param string $name      The name of the config value
 *
 * @return mixed|null
 * @see set_config()
 * @see unset_config()
 * @access private
 *
 * @deprecated Use elgg_get_config()
 */
function get_config($name) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use elgg_get_config().', '3.0');
	return elgg_get_config($name);
}

/**
 * Add an admin area section or child section.
 * This is a wrapper for elgg_register_menu_item().
 *
 * Used in conjuction with http://elgg.org/admin/section_id/child_section style
 * page handler. See the documentation at the top of this file for more details
 * on that.
 *
 * The text of the menu item is obtained from elgg_echo(admin:$parent_id:$menu_id)
 *
 * This function handles registering the parent if it has not been registered.
 *
 * @param string $section   The menu section to add to
 * @param string $menu_id   The unique ID of section
 * @param string $parent_id If a child section, the parent section id
 * @param int    $priority  The menu item priority
 *
 * @return bool
 * @since 1.8.0
 *
 * @deprecated Use elgg_remove_config()
 */
function elgg_register_admin_menu_item($section, $menu_id, $parent_id = null, $priority = 100) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use elgg_register_menu_item().', '3.0');
	// make sure parent is registered
	if ($parent_id && !elgg_is_menu_item_registered('page', $parent_id)) {
		elgg_register_admin_menu_item($section, $parent_id);
	}

	// in the admin section parents never have links
	if ($parent_id) {
		$href = "admin/$parent_id/$menu_id";
	} else {
		$href = "admin/$menu_id";
	}

	$name = $menu_id;
	if ($parent_id) {
		$name = "$parent_id:$name";
	}

	return elgg_register_menu_item('page', [
		'name' => $name,
		'href' => $href,
		'text' => elgg_echo("admin:$name"),
		'context' => 'admin',
		'parent_name' => $parent_id,
		'priority' => $priority,
		'section' => $section
	]);
}

/**
 * Mark entities with a particular type and subtype as having access permissions
 * that can be changed independently from their parent entity
 *
 * @param string $type    The type - object, user, etc
 * @param string $subtype The subtype; all subtypes by default
 *
 * @return void
 *
 * @deprecated
 */
function register_metadata_as_independent($type, $subtype = '*') {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Metadata no longer is access bound.', '3.0');
}

/**
 * Determines whether entities of a given type and subtype should not change
 * their metadata in line with their parent entity
 *
 * @param string $type    The type - object, user, etc
 * @param string $subtype The entity subtype
 *
 * @return bool
 *
 * @deprecated
 */
function is_metadata_independent($type, $subtype) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Metadata no longer is access bound.', '3.0');

	return false;
}

/**
 * Gets entities based upon attributes in secondary tables.
 *
 * @warning requires that the entity type be specified and there can only be one
 * type.
 *
 * @see elgg_get_entities
 *
 * @param array $options Array in format:
 *
 * 	attribute_name_value_pairs => ARR (
 *                                   'name' => 'name',
 *                                   'value' => 'value',
 *                                   'operand' => '=', (optional)
 *                                   'case_sensitive' => false (optional)
 *                                  )
 * 	                             If multiple values are sent via
 *                               an array ('value' => array('value1', 'value2')
 *                               the pair's operand will be forced to "IN".
 *
 * 	attribute_name_value_pairs_operator => null|STR The operator to use for combining
 *                                        (name = value) OPERATOR (name = value); default is AND
 *
 * @return \ElggEntity[]|mixed If count, int. If not count, array. false on errors.
 * @since 1.9.0
 * @throws InvalidArgumentException
 * @deprecated Use elgg_get_entities()
 */
function elgg_get_entities_from_attributes(array $options = []) {
    elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use elgg_get_entities.', '3.0');

    $options['metadata_name_value_pairs'] = elgg_extract('attribute_name_value_pairs', $options, []);
    $options['metadata_name_value_pairs_operator'] = elgg_extract('attribute_name_value_pairs_operator', $options, []);

    unset($options['attribute_name_value_pairs']);
    unset($options['attribute_name_value_pairs_operator']);

    return elgg_get_entities($options);
}

/**
 * Ban a user
 *
 * @param int    $user_guid The user guid
 * @param string $reason    A reason
 *
 * @return bool
 *
 * @deprecated Use \ElggUser->ban()
 */
function ban_user($user_guid, $reason = "") {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \ElggUser::ban()', '3.0');

	$user = get_user($user_guid);
	if (!$user) {
		return false;
	}

	return $user->ban($reason);
}

/**
 * Unban a user.
 *
 * @param int $user_guid Unban a user.
 *
 * @return bool
 *
 * @deprecated Use \ElggUser->unban()
 */
function unban_user($user_guid) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \ElggUser::unban()', '3.0');

	$user = get_user($user_guid);
	if (!$user) {
		return false;
	}

	return $user->unban();
}

/**
 * Makes user $guid an admin.
 *
 * @param int $user_guid User guid
 *
 * @return bool
 *
 * @deprecated Use \ElggUser->makeAdmin()
 */
function make_user_admin($user_guid) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \ElggUser::makeAdmin()', '3.0');

	$user = get_user($user_guid);
	if (!$user) {
		return false;
	}

	return $user->makeAdmin();
}

/**
 * Removes user $guid's admin flag.
 *
 * @param int $user_guid User GUID
 *
 * @return bool
 *
 * @deprecated Use \ElggUser->removeAdmin()
 */
function remove_user_admin($user_guid) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \ElggUser::removeAdmin()', '3.0');

	$user = get_user($user_guid);
	if (!$user) {
		return false;
	}

	return $user->removeAdmin();
}

/**
 * Gets the validation status of a user.
 *
 * @param int $user_guid The user's GUID
 * @return bool|null Null means status was not set for this user.
 * @since 1.8.0
 *
 * @deprecated Use \ElggUser->isValidated()
 */
function elgg_get_user_validation_status($user_guid) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \ElggUser::isValidated()', '3.0');

	$user = get_user($user_guid);
	if (!$user) {
		return false;
	}

	return $user->isValidated();
}

/**
 * Set the validation status for a user.
 *
 * @param int    $user_guid The user's GUID
 * @param bool   $status    Validated (true) or unvalidated (false)
 * @param string $method    Optional method to say how a user was validated
 * @return bool
 * @since 1.8.0
 *
 * @deprecated Use \ElggUser->setValidationStatus()
 */
function elgg_set_user_validation_status($user_guid, $status, $method = '') {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \ElggUser::setValidationStatus()', '3.0');

	$user = get_user($user_guid);
	if (!$user) {
		return false;
	}

	$user->setValidationStatus($status, $method);
	return true;
}

/**
 * Sets the last action time of the given user to right now.
 *
 * @param ElggUser|int $user The user or GUID
 * @return void
 *
 * @deprecated Use \ElggUser->setLastAction()
 */
function set_last_action($user) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \ElggUser::setLastAction()', '3.0');

	if (!$user instanceof ElggUser) {
		$user = get_user($user);
	}
	if (!$user) {
		return;
	}

	$user->setLastAction();
}

/**
 * Sets the last logon time of the given user to right now.
 *
 * @param int $user_guid The user GUID
 * @return void
 *
 * @deprecated Use \ElggUser->setLastLogin()
 */
function set_last_login($user_guid) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \ElggUser::setLastLogin()', '3.0');

	$user = get_user($user_guid);
	if (!$user) {
		return;
	}

	$user->setLastLogin();
}

/**
 * Update a specific piece of metadata.
 *
 * @param int    $id         ID of the metadata to update
 * @param string $name       Metadata name
 * @param string $value      Metadata value
 * @param string $value_type Value type
 *
 * @return bool
 *
 * @deprecated Use \ElggMetadata->save()
 */
function update_metadata($id, $name, $value, $value_type) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use ElggEntity setter or ElggEntity::setMetadata()', '3.0');

	$metadata = elgg_get_metadata_from_id($id);
	if (!$metadata) {
		return false;
	}

	$metadata->name = $name;
	$metadata->value_type = $value_type;
	$metadata->value = $value;

	return $metadata->save();
}

/**
 * Create a new metadata object, or update an existing one.
 *
 * Metadata can be an array by setting allow_multiple to true, but it is an
 * indexed array with no control over the indexing.
 *
 * @param int    $entity_guid    The entity to attach the metadata to
 * @param string $name           Name of the metadata
 * @param string $value          Value of the metadata
 * @param string $value_type     'text', 'integer', or '' for automatic detection
 * @param int    $ignored1       This argument is not used
 * @param null   $ignored2       This argument is not used
 * @param bool   $allow_multiple Allow multiple values for one key. Default is false
 *
 * @return int|false id of metadata or false if failure
 *
 * @deprecated Use \ElggEntity setter or \Entity->setMetadata()
 */
function create_metadata($entity_guid, $name, $value, $value_type = '', $ignored1 = null,
						 $ignored2 = null, $allow_multiple = false) {
	elgg_deprecated_notice(
		__FUNCTION__ . ' is deprecated. 
		Use ElggEntity setter or ElggEntity::setMetadata()',
		'3.0');

	$entity = get_entity($entity_guid);
	if (!$entity) {
		return false;
	}

	if ($allow_multiple) {
		return $entity->setMetadata($name, $value, $value_type, $allow_multiple);
	}

	$metadata = new ElggMetadata();
	$metadata->entity_guid = $entity_guid;
	$metadata->name = $name;
	$metadata->value_type = $value_type;
	$metadata->value = $value;
	return $metadata->save();
}

/**
 * Returns access collections owned by the entity
 *
 * @see add_access_collection()
 * @see get_members_of_access_collection()
 *
 * @param int $owner_guid GUID of the owner
 * @return \ElggAccessCollection[]|false
 *
 * @deprecated Use \Entity->getOwnedAccessCollections() or elgg_get_access_collections()
 */
function get_user_access_collections($owner_guid) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \ElggEntity->getOwnedAccessCollections() or elgg_get_access_collections()', '3.0');

	return _elgg_services()->accessCollections->getEntityCollections(['owner_guid' => $owner_guid]);
}


/**
 * Returns entities based upon metadata.  Also accepts all
 * options available to elgg_get_entities().  Supports
 * the singular option shortcut.
 *
 * @note Using metadata_names and metadata_values results in a
 * "names IN (...) AND values IN (...)" clause.  This is subtly
 * differently than default multiple metadata_name_value_pairs, which use
 * "(name = value) AND (name = value)" clauses.
 *
 * When in doubt, use name_value_pairs.
 *
 * To ask for entities that do not have a metadata value, use a custom
 * where clause like this:
 *
 * 	$options['wheres'][] = "NOT EXISTS (
 *			SELECT 1 FROM {$dbprefix}metadata md
 *			WHERE md.entity_guid = e.guid
 *				AND md.name = $name
 *				AND md.value = $value)";
 *
 * @see elgg_get_entities
 *
 * @param array $options Array in format:
 *
 * 	metadata_names => null|ARR metadata names
 *
 * 	metadata_values => null|ARR metadata values
 *
 * 	metadata_name_value_pairs => null|ARR (
 *                                         name => 'name',
 *                                         value => 'value',
 *                                         'operand' => '=',
 *                                         'case_sensitive' => true
 *                                        )
 *                               Currently if multiple values are sent via
 *                               an array (value => array('value1', 'value2')
 *                               the pair's operand will be forced to "IN".
 *                               If passing "IN" as the operand and a string as the value,
 *                               the value must be a properly quoted and escaped string.
 *
 * 	metadata_name_value_pairs_operator => null|STR The operator to use for combining
 *                                        (name = value) OPERATOR (name = value); default AND
 *
 * 	metadata_case_sensitive => BOOL Overall Case sensitive
 *
 *  order_by_metadata => null|ARR array(
 *                                      'name' => 'metadata_text1',
 *                                      'direction' => ASC|DESC,
 *                                      'as' => text|integer
 *                                     )
 *                                Also supports array('name' => 'metadata_text1')
 *
 * @return \ElggEntity[]|mixed If count, int. If not count, array. false on errors.
 * @since 1.7.0
 *
 * @deprecated 3.0
 */
function elgg_get_entities_from_metadata(array $options = []) {
	elgg_deprecated_notice(
		__FUNCTION__ . ' has been deprecated.
		elgg_get_entities() now accepts all metadata options.
	', '3.0');

	return elgg_get_entities($options);
}

/**
 * Returns a list of entities filtered by provided metadata.
 *
 * @see elgg_get_entities
 *
 * @param array $options Options array
 *
 * @return array
 * @since 1.7.0
 * @deprecated 3.0
 */
function elgg_list_entities_from_metadata($options) {
	elgg_deprecated_notice(
		__FUNCTION__ . ' has been deprecated. Use elgg_list_entities().
	', '3.0');

	return elgg_list_entities($options);
}

/**
 * Returns entities based upon annotations.
 *
 * Entity creation time is selected as maxtime. To sort based upon
 * this, pass 'order_by' => 'maxtime asc' || 'maxtime desc'
 *
 * @see elgg_get_entities
 *
 * @param array $options Array in format:
 *
 * 	annotation_names => null|ARR annotations names
 *
 * 	annotation_values => null|ARR annotations values
 *
 * 	annotation_name_value_pairs => null|ARR (name = 'name', value => 'value',
 * 	'operator' => '=', 'case_sensitive' => true) entries.
 * 	Currently if multiple values are sent via an array (value => array('value1', 'value2')
 * 	the pair's operator will be forced to "IN".
 *
 * 	annotation_name_value_pairs_operator => null|STR The operator to use for combining
 *  (name = value) OPERATOR (name = value); default AND
 *
 * 	annotation_case_sensitive => BOOL Overall Case sensitive
 *
 *  order_by_annotation => null|ARR (array('name' => 'annotation_text1', 'direction' => ASC|DESC,
 *  'as' => text|integer),
 *
 *  Also supports array('name' => 'annotation_text1')
 *
 *  annotation_owner_guids => null|ARR guids for annotaiton owners
 *
 * @return mixed If count, int. If not count, array. false on errors.
 * @since 1.7.0
 */
function elgg_get_entities_from_annotations(array $options = []) {
	elgg_deprecated_notice(
		__FUNCTION__ . ' has been deprecated.
		elgg_get_entities() now accepts all annotation options
	', '3.0');

	return elgg_get_entities($options);
}

/**
 * Returns a viewable list of entities from annotations.
 *
 * @param array $options Options array
 *
 * @see elgg_get_entities_from_annotations()
 * @see elgg_list_entities()
 *
 * @return string
 * @deprecated 3.0
 */
function elgg_list_entities_from_annotations($options = []) {
	elgg_deprecated_notice(
		__FUNCTION__ . ' has been deprecated. Use elgg_list_entities().
	', '3.0');
	return elgg_list_entities($options);
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
 *
 * @deprecated 3.0
 */
function elgg_get_entities_from_relationship($options) {
	elgg_deprecated_notice(
		__FUNCTION__ . ' has been deprecated.
		elgg_get_entities() now accepts all relationship options.
	', '3.0');

	return elgg_get_entities($options);
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
 * @deprecated 3.0
 */
function elgg_list_entities_from_relationship(array $options = []) {
	elgg_deprecated_notice(
		__FUNCTION__ . ' has been deprecated. Use elgg_list_entities().
	', '3.0');

	return elgg_list_entities($options);
}

/**
 * Returns entities based upon private settings.  Also accepts all
 * options available to elgg_get_entities().  Supports
 * the singular option shortcut.
 *
 * @see elgg_get_entities
 *
 * @param array $options Array in format:
 *
 * 	private_setting_names => null|ARR private setting names
 *
 * 	private_setting_values => null|ARR metadata values
 *
 * 	private_setting_name_value_pairs => null|ARR (
 *                                         name => 'name',
 *                                         value => 'value',
 *                                         'operand' => '=',
 *                                        )
 * 	                             Currently if multiple values are sent via
 *                               an array (value => array('value1', 'value2')
 *                               the pair's operand will be forced to "IN".
 *
 * 	private_setting_name_value_pairs_operator => null|STR The operator to use for combining
 *                                        (name = value) OPERATOR (name = value); default AND
 *
 *  private_setting_name_prefix => STR A prefix to apply to all private settings. Used to
 *                                     namespace plugin user settings or by plugins to namespace
 *                                     their own settings.
 *
 *
 * @return mixed int If count, int. If not count, array. false on errors.
 * @since 1.8.0
 * @deprecated 3.0
 */
function elgg_get_entities_from_private_settings(array $options = []) {
	elgg_deprecated_notice(
		__FUNCTION__ . ' has been deprecated.
		elgg_get_entities() now accepts all private settings options.
	', '3.0');

	return elgg_get_entities($options);
}

/**
 * Lists entities from an access collection
 *
 * @param array $options See elgg_list_entities() and elgg_get_entities_from_access_id()
 *
 * @see elgg_list_entities()
 * @see elgg_get_entities_from_access_id()
 *
 * @return string
 * @deprecated 3.0
 */
function elgg_list_entities_from_access_id(array $options = []) {
	elgg_deprecated_notice(
		__FUNCTION__ . ' has been deprecated. Use elgg_list_entities().
	', '3.0');

	return elgg_list_entities($options);
}

/**
 * Return entities based upon access id.
 *
 * @param array $options Any options accepted by {@link elgg_get_entities()} and
 * 	access_id => int The access ID of the entity.
 *
 * @see elgg_get_entities()
 * @return mixed If count, int. If not count, array. false on errors.
 * @since 1.7.0
 *
 * @deprected 3.0
 */
function elgg_get_entities_from_access_id(array $options = []) {

	elgg_deprecated_notice(
		__FUNCTION__ . ' has been deprecated.
		Use elgg_get_entities() with "access_ids" option.
	', '3.0');

	// restrict the resultset to access collection provided
	if (!isset($options['access_id']) && !isset($options['access_ids'])) {
		return false;
	}

	return elgg_get_entities($options);
}

/**
 * Get entities ordered by a mathematical calculation on annotation values
 *
 * @tip Note that this function uses { @link elgg_get_annotations() } to return a list of entities ordered by a mathematical
 * calculation on annotation values, and { @link elgg_get_entities_from_annotations() } to return a count of entities
 * if $options['count'] is set to a truthy value
 *
 * @param array $options An options array:
 * 	'calculation'            => The calculation to use. Must be a valid MySQL function.
 *                              Defaults to sum.  Result selected as 'annotation_calculation'.
 *                              Don't confuse this "calculation" option with the
 *                              "annotation_calculation" option to elgg_get_annotations().
 *                              This "calculation" option is applied to each entity's set of
 *                              annotations and is selected as annotation_calculation for that row.
 *                              See the docs for elgg_get_annotations() for proper use of the
 *                              "annotation_calculation" option.
 *	'order_by'               => The order for the sorting. Defaults to 'annotation_calculation desc'.
 *	'annotation_names'       => The names of annotations on the entity.
 *	'annotation_values'	     => The values of annotations on the entity.
 *
 *	'metadata_names'         => The name of metadata on the entity.
 *	'metadata_values'        => The value of metadata on the entitiy.
 *	'callback'               => Callback function to pass each row through.
 *                              @tip This function is different from other ege* functions,
 *                              as it uses a metastring-based getter function { @link elgg_get_annotations() },
 *                              therefore the callback function should be a derivative of { @link entity_row_to_elggstar() }
 *                              and not of { @link row_to_annotation() }
 *
 * @return \ElggEntity[]|int An array or a count of entities
 * @see elgg_get_annotations()
 * @see elgg_get_entities_from_annotations()
 *
 * @deprecated
 */
function elgg_get_entities_from_annotation_calculation($options) {
	elgg_deprecated_notice(
		__FUNCTION__ . ' has been deprecated.
		Use elgg_get_entities() with "annotation_sort_by_calculation" option.
		To sort in an ascending order, pass "order_by" => new OrderByClause("annotation_calculation", "asc")
	', '3.0');

	if (empty($options['count'])) {
		$options['annotation_sort_by_calculation'] = elgg_extract('calculation', $options, 'sum', false);
	}
	return Entities::find($options);
}

/**
 * List entities from an annotation calculation.
 *
 * @see elgg_get_entities_from_annotation_calculation()
 *
 * @param array $options An options array.
 *
 * @return string
 *
 * @deprecated
 */
function elgg_list_entities_from_annotation_calculation($options) {
	elgg_deprecated_notice(
		__FUNCTION__ . ' has been deprecated.
		Use elgg_get_entities() with "annotation_sort_by_calculation" option.
		To sort in an ascending order, pass "order_by" => new OrderByClause("annotation_calculation", "asc")
	', '3.0');

	if (empty($options['count'])) {
		$options['annotation_sort_by_calculation'] = elgg_extract('calculation', $options, 'sum', false);
	}

	return elgg_list_entities($options, 'elgg_get_entities');
}


/**
 * Enables or disables a metastrings-based object by its id.
 *
 * @warning To enable disabled metastrings you must first use
 * {@link access_show_hidden_entities()}.
 *
 * @param int    $id      The object's ID
 * @param string $enabled Value to set to: yes or no
 * @param string $type    Metastring type: metadata or annotation
 *
 * @return bool
 * @throws InvalidParameterException
 * @access private
 *
 * @deprecated
 */
function _elgg_set_metastring_based_object_enabled_by_id($id, $enabled, $type) {

	elgg_deprecated_notice(
		__FUNCTION__ . ' has been deprecated.
		Use ElggAnnotation::enable()
	', '3.0');

	if (!in_array($type, ['annotation', 'annotations'])) {
		return false;
	}

	$annotation = elgg_get_annotation_from_id($id);
	if (!$annotation) {
		return false;
	}

	if ($enabled === 'no' || $enabled === 0 || $enabled === false) {
		return $annotation->disable();
	} else if ($enabled === 'yes' || $enabled === 1 || $enabled === true) {
		return $annotation->enable();
	}

	return false;
}

/**
 * Returns a singular metastring-based object by its ID.
 *
 * @param int    $id   The metastring-based object's ID
 * @param string $type The type: annotation or metadata
 * @return \ElggExtender
 * @access private
 *
 * @deprecated
 */
function _elgg_get_metastring_based_object_from_id($id, $type) {

	elgg_deprecated_notice(
		__FUNCTION__ . ' has been deprecated.
		Use elgg_get_metadata_from_id() and elgg_get_annotation_from_id()
	', '3.0');

	$id = (int) $id;
	if (!$id) {
		return false;
	}

	if ($type == 'metadata') {
		$object = elgg_get_metadata_from_id($id);
	} else {
		$object = elgg_get_annotation_from_id($id);
	}

	return $object;
}

/**
 * Deletes a metastring-based object by its id
 *
 * @param int    $id   The object's ID
 * @param string $type The object's metastring type: annotation or metadata
 * @return bool
 * @access private
 *
 * @deprected
 */
function _elgg_delete_metastring_based_object_by_id($id, $type) {

	elgg_deprecated_notice(
		__FUNCTION__ . ' has been deprecated.
		Use ElggMetadata::delete() and ElggAnnotation::delete()
	', '3.0');

	switch ($type) {
		case 'annotations':
		case 'annotation':
			$object = elgg_get_annotation_from_id($id);
			break;

		case 'metadata':
			$object = elgg_get_metadata_from_id($id);
			break;

		default:
			return false;
	}

	if ($object) {
		return $object->delete();
	}

	return false;
}

/**
 * Get the URL for this metadata
 *
 * By default this links to the export handler in the current view.
 *
 * @param int $id Metadata ID
 *
 * @return mixed
 * @deprecated 3.0
 */
function get_metadata_url($id) {
	elgg_deprecated_notice(__FUNCTION__ . ' has been deprecated and will be removed', '3.0');
	$metadata = elgg_get_metadata_from_id($id);
	if (!$metadata instanceof ElggMetadata) {
		return;
	}

	return $metadata->getURL();
}


/**
 * Create a new annotation.
 *
 * @param int    $entity_guid GUID of entity to be annotated
 * @param string $name        Name of annotation
 * @param string $value       Value of annotation
 * @param string $value_type  Type of value (default is auto detection)
 * @param int    $owner_guid  Owner of annotation (default is logged in user)
 * @param int    $access_id   Access level of annotation
 *
 * @return int|bool id on success or false on failure
 * @deprecated 3.0
 */
function create_annotation($entity_guid, $name, $value, $value_type = '', $owner_guid = 0, $access_id = ACCESS_PRIVATE) {
	elgg_deprecated_notice(
		__FUNCTION__ . ' has been deprecated.
		Use ElggAnnotation::save() or ElggEntity::annotate()
		', '3.0'
	);

	$annotation = new ElggAnnotation();
	$annotation->entity_guid = $entity_guid;
	$annotation->name = $name;
	$annotation->value_type = $value_type;
	$annotation->value = $value;
	$annotation->owner_guid = $owner_guid;
	$annotation->access_id = $access_id;

	return $annotation->save();
}

/**
 * Update an annotation.
 *
 * @param int    $annotation_id Annotation ID
 * @param string $name          Name of annotation
 * @param string $value         Value of annotation
 * @param string $value_type    Type of value
 * @param int    $owner_guid    Owner of annotation
 * @param int    $access_id     Access level of annotation
 *
 * @return bool
 */
function update_annotation($annotation_id, $name, $value, $value_type, $owner_guid, $access_id) {
	elgg_deprecated_notice(
		__FUNCTION__ . ' has been deprecated.
		Use ElggAnnotation::save() or ElggEntity::annotate()
		', '3.0'
	);

	$annotation = elgg_get_annotation_from_id($annotation_id);
	if (!$annotation) {
		return false;
	}

	$annotation->name = $name;
	$annotation->value_type = $value_type;
	$annotation->value = $value;
	$annotation->owner_guid = $owner_guid;
	$annotation->access_id = $access_id;

	return $annotation->save();
}

/**
 * Delete objects with a delete() method.
 *
 * Used as a callback for \ElggBatch.
 *
 * @param object $object The object to disable
 * @return bool
 * @access private
 *
 * @deprecated 3.0
 */
function elgg_batch_delete_callback($object) {
	elgg_deprecated_notice(__FUNCTION__ . ' has been deprecated.', '3.0');

	// our db functions return the number of rows affected...
	return $object->delete() ? true : false;
}

/**
 * Sanitise file paths ensuring that they begin and end with slashes etc.
 *
 * @param string $path         The path
 * @param bool   $append_slash Add tailing slash
 *
 * @return string
 * @deprecated 3.0
 */
function sanitise_filepath($path, $append_slash = true) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \\Elgg\\Project\\Paths::sanitize().', 3.0);

	return \Elgg\Project\Paths::sanitize($path, $append_slash);
}

/**
 * Registers a page handler for a particular identifier
 *
 * For example, you can register a function called 'blog_page_handler' for the identifier 'blog'
 * For all URLs  http://yoururl/blog/*, the blog_page_handler() function will be called.
 * The part of the URL marked with * above will be exploded on '/' characters and passed as an
 * array to that function.
 * For example, the URL http://yoururl/blog/username/friends/ would result in the call:
 * blog_page_handler(array('username','friends'), blog);
 *
 * A request to register a page handler with the same identifier as previously registered
 * handler will replace the previous one.
 *
 * The context is set to the identifier before the registered
 * page handler function is called. For the above example, the context is set to 'blog'.
 *
 * Page handlers should return true to indicate that they handled the request.
 * Requests not handled are forwarded to the front page with a reason of 404.
 * Plugins can register for the 'forward', '404' plugin hook. @see forward()
 *
 * @param string $identifier The page type identifier
 * @param string $function   Your function name
 *
 * @return bool Depending on success
 * @deprecated 3.0
 */
function elgg_register_page_handler($identifier, callable $function) {
	elgg_deprecated_notice(
		__FUNCTION__ . ' has been deprecated. 
		Use elgg_register_route() to register a named route or define it in elgg-plugin.php',
		'3.0'
	);
	return _elgg_services()->router->registerPageHandler($identifier, $function);
}

/**
 * Unregister a page handler for an identifier
 *
 * Note: to replace a page handler, call elgg_register_page_handler()
 *
 * @param string $identifier The page type identifier
 *
 * @since 1.7.2
 * @return void
 * @deprecated
 */
function elgg_unregister_page_handler($identifier) {
	elgg_deprecated_notice(
		__FUNCTION__ . ' has been deprecated. 
		Use new routing API to register and unregister routes.',
		'3.0'
	);
	_elgg_services()->router->unregisterPageHandler($identifier);
}

/**
 * Alias of elgg_gatekeeper()
 *
 * Used at the top of a page to mark it as logged in users only.
 *
 * @return void
 * @throws \Elgg\GatekeeperException
 * @deprecated 3.0
 */
function gatekeeper() {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use elgg_gatekeeper()', '3.0');
	elgg_gatekeeper();
}

/**
 * Alias of elgg_admin_gatekeeper()
 *
 * Used at the top of a page to mark it as logged in admin or siteadmin only.
 *
 * @return void
 * @throws \Elgg\GatekeeperException
 * @deprecated 3.0
 */
function admin_gatekeeper() {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use elgg_admin_gatekeeper()', '3.0');
	elgg_admin_gatekeeper();
}

/**
 * May the current user access item(s) on this page? If the page owner is a group,
 * membership, visibility, and logged in status are taken into account.
 *
 * @param bool $forward         If set to true (default), will forward the page;
 *                              if set to false, will return true or false.
 *
 * @param int  $page_owner_guid The current page owner guid. If not set, this
 *                              will be pulled from elgg_get_page_owner_guid().
 *
 * @return bool Will return if $forward is set to false.
 * @deprecated 3.0
 */
function group_gatekeeper($forward = true, $page_owner_guid = null) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use elgg_group_gatekeeper()', '3.0');
	return elgg_group_gatekeeper($forward, $page_owner_guid);
}