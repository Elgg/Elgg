<?php
/**
 * Functions for Elgg's access system for entities, metadata, and annotations.
 *
 * Access is generally saved in the database as access_id.  This corresponds to
 * one of the ACCESS_* constants defined in {@link elgglib.php} or the ID of an
 * access collection.
 */

/**
 * Get current ignore access setting.
 *
 * @return bool
 * @since 1.7.0
 */
function elgg_get_ignore_access(): bool {
	return _elgg_services()->session_manager->getIgnoreAccess();
}

/**
 * Returns an array of access IDs a user is permitted to see.
 *
 * Can be overridden with the 'access:collections:read', 'user' event.
 * @warning A callback for that event needs to either not retrieve data
 * from the database that would use the access system (triggering the plugin again)
 * or ignore the second call. Otherwise, an infinite loop will be created.
 *
 * This returns a list of all the collection ids a user owns or belongs to
 * plus public and logged in access levels. If the user is an admin, it includes
 * the private access level.
 *
 * @see elgg_get_write_access_array() for the access levels that a user can write to.
 *
 * @param int $user_guid User ID; defaults to currently logged in user
 *
 * @return array An array of access collections ids
 * @since 4.3
 */
function elgg_get_access_array(int $user_guid = 0): array {
	return _elgg_services()->accessCollections->getAccessArray($user_guid);
}

/**
 * Gets the default access permission.
 *
 * This returns the default access level for the site or optionally of the user.
 * If want you to change the default access based on group of other information,
 * use the 'default', 'access' event.
 *
 * @param \ElggUser $user         The user for whom we're getting default access. Defaults to logged in user.
 * @param array     $input_params Parameters passed into an input/access view
 *
 * @return int default access id (see ACCESS defines in constants.php)
 * @since 4.3
 */
function elgg_get_default_access(\ElggUser $user = null, array $input_params = []): int {
	// site default access
	$default_access = (int) _elgg_services()->config->default_access;
	
	// default to logged in user
	$user = $user ?? _elgg_services()->session_manager->getLoggedInUser();
	
	// user default access if enabled
	if (_elgg_services()->config->allow_user_default_access && $user instanceof \ElggUser) {
		$user_access = $user->elgg_default_access;
		if ($user_access !== null) {
			$default_access = (int) $user_access;
		}
	}
	
	$params = [
		'user' => $user,
		'default_access' => $default_access,
		'input_params' => $input_params,
	];
	return (int) _elgg_services()->events->triggerResults('default', 'access', $params, $default_access);
}

/**
 * Can a user access an entity.
 *
 * @tip This is mostly useful for checking if a user other than the logged in
 * user has access to an entity that is currently loaded.
 *
 * @param int $entity_guid The entity_guid to check access for
 * @param int $user_guid   Optionally user_guid to check access with (defaults to logged in user)
 *
 * @return bool
 * @since 4.3
 */
function elgg_has_access_to_entity(int $entity_guid, int $user_guid = 0): bool {
	$entity = elgg_call(ELGG_IGNORE_ACCESS, function() use ($entity_guid) {
		return _elgg_services()->entityTable->get($entity_guid);
	});
	
	if (!$entity instanceof \ElggEntity) {
		return false;
	}
	
	return _elgg_services()->accessCollections->hasAccessToEntity($entity, $user_guid);
}

/**
 * Returns an array of access permissions that the user is allowed to save content with.
 * Permissions returned are of the form (id => 'name').
 *
 * Example return value in English:
 * array(
 *     0 => 'Private',
 *    -2 => 'Friends',
 *     1 => 'Logged in users',
 *     2 => 'Public',
 *    34 => 'My favorite friends',
 * );
 *
 * Event of 'access:collections:write', 'user'
 *
 * @warning this only returns access collections that the user owns plus the
 * standard access levels. It does not return access collections that the user
 * belongs to such as the access collection for a group.
 *
 * @param int   $user_guid    The user's GUID
 * @param bool  $flush        If this is set to true, this will ignore a cached access array
 * @param array $input_params Some parameters passed into an input/access view
 *
 * @return array List of access permissions
 * @since 4.3
 */
function elgg_get_write_access_array(int $user_guid = 0, bool $flush = false, array $input_params = []): array {
	return _elgg_services()->accessCollections->getWriteAccessArray($user_guid, $flush, $input_params);
}

/**
 * Creates a new access collection.
 *
 * Access colletions allow plugins and users to create granular access for entities.
 *
 * Triggers 'create', 'access_collection' event sequence
 *
 * @param string $name       The name of the collection
 * @param int    $owner_guid The GUID of the owner (default: currently logged in user)
 * @param string $subtype    The subtype indicates the usage of the acl
 *
 * @return \ElggAccessCollection|null
 * @since 4.3
 */
function elgg_create_access_collection(string $name, int $owner_guid = 0, string $subtype = null): ?\ElggAccessCollection {
	$acl = new \ElggAccessCollection();
	$acl->name = $name;
	$acl->owner_guid = $owner_guid ?: _elgg_services()->session_manager->getLoggedInUserGuid();
	$acl->subtype = $subtype;
	
	return $acl->save() ? $acl : null;
}

/**
 * Get a specified access collection
 *
 * @param int $collection_id The collection ID
 *
 * @return \ElggAccessCollection|null
 * @since 4.3
 */
function elgg_get_access_collection(int $collection_id): ?\ElggAccessCollection {
	return _elgg_services()->accessCollections->get($collection_id);
}

/**
 * Returns access collections
 *
 * @param array $options array of options to get access collections by
 *
 * @return \ElggAccessCollection[]
 */
function elgg_get_access_collections(array $options = []): array {
	return _elgg_services()->accessCollections->getEntityCollections($options);
}

/**
 * Return the name of an ACCESS_* constant or an access collection,
 * but only if the logged in user has write access to it.
 * Write access requirement prevents us from exposing names of access collections
 * that current user has been added to by other members and may contain
 * sensitive classification of the current user (e.g. close friends vs acquaintances).
 *
 * Returns a string in the language of the user for global access levels, e.g.'Public, 'Friends', 'Logged in', 'Public';
 * or a name of the owned access collection, e.g. 'My work colleagues';
 * or a name of the group or other access collection, e.g. 'Group: Elgg technical support';
 * or 'Limited' if the user access is restricted to read-only, e.g. a friends collection the user was added to
 *
 * @param int $entity_access_id The entity's access id
 *
 * @return string
 * @since 4.3
 */
function elgg_get_readable_access_level(int $entity_access_id): string {
	return _elgg_services()->accessCollections->getReadableAccessLevel($entity_access_id);
}
