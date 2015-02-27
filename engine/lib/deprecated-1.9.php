<?php

/**
 * Return the full URL of the current page.
 *
 * @return string The URL
 * @todo Combine / replace with current_page_url(). full_url() is based on the
 * request only while current_page_url() uses the configured site url.
 * @deprecated 1.9 Use current_page_url()
 */
function full_url() {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use current_page_url()', 1.9);
	$request = _elgg_services()->request;
	$url = $request->getSchemeAndHttpHost();

	// This is here to prevent XSS in poorly written browsers used by 80% of the population.
	// svn commit [5813]: https://github.com/Elgg/Elgg/commit/0c947e80f512cb0a482b1864fd0a6965c8a0cd4a
	// @todo encoding like this should occur when inserting into web page, not here
	$quotes = array('\'', '"');
	$encoded = array('%27', '%22');
	return $url . str_replace($quotes, $encoded, $request->getRequestUri());
}

/**
 * Sets the URL handler for a particular entity type and subtype
 *
 * @param string $entity_type    The entity type
 * @param string $entity_subtype The entity subtype
 * @param string $function_name  The function to register
 *
 * @return bool Depending on success
 * @see get_entity_url()
 * @see \ElggEntity::getURL()
 * @since 1.8.0
 * @deprecated 1.9.0 Use the plugin hook in \ElggEntity::getURL()
 */
function elgg_register_entity_url_handler($entity_type, $entity_subtype, $function_name) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use the plugin hook in \ElggEntity::getURL()', 1.9);
	global $CONFIG;

	if (!is_callable($function_name, true)) {
		return false;
	}

	if (!isset($CONFIG->entity_url_handler)) {
		$CONFIG->entity_url_handler = array();
	}

	if (!isset($CONFIG->entity_url_handler[$entity_type])) {
		$CONFIG->entity_url_handler[$entity_type] = array();
	}

	$CONFIG->entity_url_handler[$entity_type][$entity_subtype] = $function_name;

	return true;
}

/**
 * Sets the URL handler for a particular relationship type
 *
 * @param string $relationship_type The relationship type.
 * @param string $function_name     The function to register
 *
 * @return bool Depending on success
 * @deprecated 1.9 Use the plugin hook in \ElggRelationship::getURL()
 */
function elgg_register_relationship_url_handler($relationship_type, $function_name) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use the plugin hook in getURL()', 1.9);
	global $CONFIG;

	if (!is_callable($function_name, true)) {
		return false;
	}

	if (!isset($CONFIG->relationship_url_handler)) {
		$CONFIG->relationship_url_handler = array();
	}

	$CONFIG->relationship_url_handler[$relationship_type] = $function_name;

	return true;
}

/**
 * Get the url for a given relationship.
 *
 * @param int $id Relationship ID
 *
 * @return string
 * @deprecated 1.9 Use \ElggRelationship::getURL()
 */
function get_relationship_url($id) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \ElggRelationship::getURL()', 1.9);
	global $CONFIG;

	$id = (int)$id;

	if ($relationship = get_relationship($id)) {
		$view = elgg_get_viewtype();

		$guid = $relationship->guid_one;
		$type = $relationship->relationship;

		$url = "";

		$function = "";
		if (isset($CONFIG->relationship_url_handler[$type])) {
			$function = $CONFIG->relationship_url_handler[$type];
		}
		if (isset($CONFIG->relationship_url_handler['all'])) {
			$function = $CONFIG->relationship_url_handler['all'];
		}

		if (is_callable($function)) {
			$url = call_user_func($function, $relationship);
		}

		if ($url == "") {
			$nameid = $relationship->id;

			$url = elgg_get_site_url()  . "export/$view/$guid/relationship/$nameid/";
		}

		return $url;
	}

	return false;
}

/**
 * Sets the URL handler for a particular extender type and name.
 * It is recommended that you do not call this directly, instead use
 * one of the wrapper functions such as elgg_register_annotation_url_handler().
 *
 * @param string $extender_type Extender type ('annotation', 'metadata')
 * @param string $extender_name The name of the extender
 * @param string $function_name The function to register
 *
 * @return bool
 * @deprecated 1.9 Use plugin hook in \ElggExtender::getURL()
 */
function elgg_register_extender_url_handler($extender_type, $extender_name, $function_name) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use the plugin hook in getURL()', 1.9, 2);

	global $CONFIG;

	if (!is_callable($function_name, true)) {
		return false;
	}

	if (!isset($CONFIG->extender_url_handler)) {
		$CONFIG->extender_url_handler = array();
	}
	if (!isset($CONFIG->extender_url_handler[$extender_type])) {
		$CONFIG->extender_url_handler[$extender_type] = array();
	}
	$CONFIG->extender_url_handler[$extender_type][$extender_name] = $function_name;

	return true;
}

/**
 * Get the URL of a given elgg extender.
 * Used by get_annotation_url and get_metadata_url.
 *
 * @param \ElggExtender $extender An extender object
 *
 * @return string
 * @deprecated 1.9 Use method getURL()
 */
function get_extender_url(\ElggExtender $extender) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \ElggExtender::getURL()', 1.9);
	global $CONFIG;

	$view = elgg_get_viewtype();

	$guid = $extender->entity_guid;
	$type = $extender->type;

	$url = "";

	$function = "";
	if (isset($CONFIG->extender_url_handler[$type][$extender->name])) {
		$function = $CONFIG->extender_url_handler[$type][$extender->name];
	}

	if (isset($CONFIG->extender_url_handler[$type]['all'])) {
		$function = $CONFIG->extender_url_handler[$type]['all'];
	}

	if (isset($CONFIG->extender_url_handler['all']['all'])) {
		$function = $CONFIG->extender_url_handler['all']['all'];
	}

	if (is_callable($function)) {
		$url = call_user_func($function, $extender);
	}

	if ($url == "") {
		$nameid = $extender->id;
		if ($type == 'volatile') {
			$nameid = $extender->name;
		}
		$url = "export/$view/$guid/$type/$nameid/";
	}

	return elgg_normalize_url($url);
}

/**
 * Get the URL for this annotation.
 *
 * @param int $id Annotation id
 *
 * @return string|bool False on failure
 * @deprecated 1.9 Use method getURL() on annotation object
 */
function get_annotation_url($id) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \ElggAnnotation::getURL()', 1.9);
	$id = (int)$id;

	if ($extender = elgg_get_annotation_from_id($id)) {
		return get_extender_url($extender);
	}
	return false;
}

/**
 * Register a metadata url handler.
 *
 * @param string $extender_name The name, default 'all'.
 * @param string $function      The function name.
 *
 * @return bool
 * @deprecated 1.9 Use the plugin hook in \ElggExtender::getURL()
 */
function elgg_register_metadata_url_handler($extender_name, $function) {
	// deprecation notice comes from elgg_register_extender_url_handler()
	return elgg_register_extender_url_handler('metadata', $extender_name, $function);
}

/**
 * Register an annotation url handler.
 *
 * @param string $extender_name The name, default 'all'.
 * @param string $function_name The function.
 *
 * @return string
 * @deprecated 1.9 Use the plugin hook in \ElggExtender::getURL()
 */
function elgg_register_annotation_url_handler($extender_name = "all", $function_name) {
	// deprecation notice comes from elgg_register_extender_url_handler()
	return elgg_register_extender_url_handler('annotation', $extender_name, $function_name);
}

/**
 * Return a list of this group's members.
 *
 * @param int  $group_guid The ID of the container/group.
 * @param int  $limit      The limit
 * @param int  $offset     The offset
 * @param int  $site_guid  The site
 * @param bool $count      Return the users (false) or the count of them (true)
 *
 * @return mixed
 * @deprecated 1.9 Use \ElggGroup::getMembers()
 */
function get_group_members($group_guid, $limit = 10, $offset = 0, $site_guid = 0, $count = false) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \ElggGroup::getMembers()', 1.9);

	// in 1.7 0 means "not set."  rewrite to make sense.
	if (!$site_guid) {
		$site_guid = ELGG_ENTITIES_ANY_VALUE;
	}

	return elgg_get_entities_from_relationship(array(
		'relationship' => 'member',
		'relationship_guid' => $group_guid,
		'inverse_relationship' => true,
		'type' => 'user',
		'limit' => $limit,
		'offset' => $offset,
		'count' => $count,
		'site_guid' => $site_guid
	));
}

/**
 * Add an object to the given group.
 *
 * @param int $group_guid  The group to add the object to.
 * @param int $object_guid The guid of the elgg object (must be \ElggObject or a child thereof)
 *
 * @return bool
 * @throws InvalidClassException
 * @deprecated 1.9 Use \ElggGroup::addObjectToGroup()
 */
function add_object_to_group($group_guid, $object_guid) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \ElggGroup::addObjectToGroup()', 1.9);
	$group_guid = (int)$group_guid;
	$object_guid = (int)$object_guid;

	$group = get_entity($group_guid);
	$object = get_entity($object_guid);

	if ((!$group) || (!$object)) {
		return false;
	}

	if (!($group instanceof \ElggGroup)) {
		$msg = "GUID:" . $group_guid . " is not a valid " . '\ElggGroup';
		throw new \InvalidClassException($msg);
	}

	if (!($object instanceof \ElggObject)) {
		$msg = "GUID:" . $object_guid . " is not a valid " . '\ElggObject';
		throw new \InvalidClassException($msg);
	}

	$object->container_guid = $group_guid;
	return $object->save();
}

/**
 * Remove an object from the given group.
 *
 * @param int $group_guid  The group to remove the object from
 * @param int $object_guid The object to remove
 *
 * @return bool
 * @throws InvalidClassException
 * @deprecated 1.9 Use \ElggGroup::removeObjectFromGroup()
 */
function remove_object_from_group($group_guid, $object_guid) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \ElggGroup::removeObjectFromGroup()', 1.9);
	$group_guid = (int)$group_guid;
	$object_guid = (int)$object_guid;

	$group = get_entity($group_guid);
	$object = get_entity($object_guid);

	if ((!$group) || (!$object)) {
		return false;
	}

	if (!($group instanceof \ElggGroup)) {
		$msg = "GUID:" . $group_guid . " is not a valid " . '\ElggGroup';
		throw new \InvalidClassException($msg);
	}

	if (!($object instanceof \ElggObject)) {
		$msg = "GUID:" . $object_guid . " is not a valid " . '\ElggObject';
		throw new \InvalidClassException($msg);
	}

	$object->container_guid = $object->owner_guid;
	return $object->save();
}

/**
 * Return whether a given user is a member of the group or not.
 *
 * @param int $group_guid The group ID
 * @param int $user_guid  The user guid
 *
 * @return bool
 * @deprecated 1.9 Use Use \ElggGroup::isMember()
 */
function is_group_member($group_guid, $user_guid) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \ElggGroup::isMember()', 1.9);
	$object = check_entity_relationship($user_guid, 'member', $group_guid);
	if ($object) {
		return true;
	} else {
		return false;
	}
}


/**
 * Return all groups a user is a member of.
 *
 * @param int $user_guid GUID of user
 *
 * @return array|false
 * @deprecated 1.9 Use \ElggUser::getGroups()
 */
function get_users_membership($user_guid) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \ElggUser::getGroups()', 1.9);
	$options = array(
		'type' => 'group',
		'relationship' => 'member',
		'relationship_guid' => $user_guid,
		'inverse_relationship' => false,
		'limit' => false,
	);
	return elgg_get_entities_from_relationship($options);
}

/**
 * Determines whether or not a user is another user's friend.
 *
 * @param int $user_guid   The GUID of the user
 * @param int $friend_guid The GUID of the friend
 *
 * @return bool
 * @deprecated 1.9 Use \ElggUser::isFriendsOf() or \ElggUser::isFriendsWith()
 */
function user_is_friend($user_guid, $friend_guid) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \ElggUser::isFriendsOf() or \ElggUser::isFriendsWith()', 1.9);
	return check_entity_relationship($user_guid, "friend", $friend_guid) !== false;
}

/**
 * Obtains a given user's friends
 *
 * @param int    $user_guid The user's GUID
 * @param string $subtype   The subtype of users, if any
 * @param int    $limit     Number of results to return (default 10)
 * @param int    $offset    Indexing offset, if any
 *
 * @return \ElggUser[]|false Either an array of \ElggUsers or false, depending on success
 * @deprecated 1.9 Use \ElggUser::getFriends()
 */
function get_user_friends($user_guid, $subtype = ELGG_ENTITIES_ANY_VALUE, $limit = 10,
$offset = 0) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \ElggUser::getFriends()', 1.9);

	return elgg_get_entities_from_relationship(array(
		'relationship' => 'friend',
		'relationship_guid' => $user_guid,
		'type' => 'user',
		'subtype' => $subtype,
		'limit' => $limit,
		'offset' => $offset
	));
}

/**
 * Obtains the people who have made a given user a friend
 *
 * @param int    $user_guid The user's GUID
 * @param string $subtype   The subtype of users, if any
 * @param int    $limit     Number of results to return (default 10)
 * @param int    $offset    Indexing offset, if any
 *
 * @return \ElggUser[]|false Either an array of \ElggUsers or false, depending on success
 * @deprecated 1.9 Use \ElggUser::getFriendsOf()
 */
function get_user_friends_of($user_guid, $subtype = ELGG_ENTITIES_ANY_VALUE, $limit = 10,
$offset = 0) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \ElggUser::getFriendsOf()', 1.9);

	return elgg_get_entities_from_relationship(array(
		'relationship' => 'friend',
		'relationship_guid' => $user_guid,
		'inverse_relationship' => true,
		'type' => 'user',
		'subtype' => $subtype,
		'limit' => $limit,
		'offset' => $offset
	));
}

/**
 * Adds a user to another user's friends list.
 *
 * @param int $user_guid   The GUID of the friending user
 * @param int $friend_guid The GUID of the user to friend
 *
 * @return bool Depending on success
 * @deprecated 1.9 Use \ElggUser::addFriend()
 */
function user_add_friend($user_guid, $friend_guid) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \ElggUser::addFriend()', 1.9);
	$user_guid = (int) $user_guid;
	$friend_guid = (int) $friend_guid;
	if ($user_guid == $friend_guid) {
		return false;
	}
	if (!$friend = get_entity($friend_guid)) {
		return false;
	}
	if (!$user = get_entity($user_guid)) {
		return false;
	}
	if ((!($user instanceof \ElggUser)) || (!($friend instanceof \ElggUser))) {
		return false;
	}
	return add_entity_relationship($user_guid, "friend", $friend_guid);
}

/**
 * Removes a user from another user's friends list.
 *
 * @param int $user_guid   The GUID of the friending user
 * @param int $friend_guid The GUID of the user on the friends list
 *
 * @return bool Depending on success
 * @deprecated 1.9 Use \ElggUser::removeFriend()
 */
function user_remove_friend($user_guid, $friend_guid) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \ElggUser::removeFriend()', 1.9);
	$user_guid = (int) $user_guid;
	$friend_guid = (int) $friend_guid;

	// perform cleanup for access lists.
	$collections = get_user_access_collections($user_guid);
	if ($collections) {
		foreach ($collections as $collection) {
			remove_user_from_access_collection($friend_guid, $collection->id);
		}
	}

	return remove_entity_relationship($user_guid, "friend", $friend_guid);
}

/**
 * Add a user to a site.
 *
 * @param int $site_guid Site guid
 * @param int $user_guid User guid
 *
 * @return bool
 * @deprecated 1.9 Use \ElggSite::addEntity()
 */
function add_site_user($site_guid, $user_guid) {
	elgg_deprecated_notice('add_site_user() is deprecated. Use \ElggEntity::addEntity()', 1.9);
	$site_guid = (int)$site_guid;
	$user_guid = (int)$user_guid;

	return add_entity_relationship($user_guid, "member_of_site", $site_guid);
}

/**
 * Remove a user from a site.
 *
 * @param int $site_guid Site GUID
 * @param int $user_guid User GUID
 *
 * @return bool
 * @deprecated 1.9 Use \ElggSite::removeEntity()
 */
function remove_site_user($site_guid, $user_guid) {
	elgg_deprecated_notice('remove_site_user() is deprecated. Use \ElggEntity::removeEntity()', 1.9);
	$site_guid = (int)$site_guid;
	$user_guid = (int)$user_guid;

	return remove_entity_relationship($user_guid, "member_of_site", $site_guid);
}

/**
 * Add an object to a site.
 *
 * @param int $site_guid   Site GUID
 * @param int $object_guid Object GUID
 *
 * @return mixed
 * @deprecated 1.9 Use \ElggSite::addEntity()
 */
function add_site_object($site_guid, $object_guid) {
	elgg_deprecated_notice('add_site_object() is deprecated. Use \ElggEntity::addEntity()', 1.9);
	$site_guid = (int)$site_guid;
	$object_guid = (int)$object_guid;

	return add_entity_relationship($object_guid, "member_of_site", $site_guid);
}

/**
 * Remove an object from a site.
 *
 * @param int $site_guid   Site GUID
 * @param int $object_guid Object GUID
 *
 * @return bool
 * @deprecated 1.9 Use \ElggSite::removeEntity()
 */
function remove_site_object($site_guid, $object_guid) {
	elgg_deprecated_notice('remove_site_object() is deprecated. Use \ElggEntity::removeEntity()', 1.9);
	$site_guid = (int)$site_guid;
	$object_guid = (int)$object_guid;

	return remove_entity_relationship($object_guid, "member_of_site", $site_guid);
}

/**
 * Get the objects belonging to a site.
 *
 * @param int    $site_guid Site GUID
 * @param string $subtype   Subtype
 * @param int    $limit     Limit
 * @param int    $offset    Offset
 *
 * @return mixed
 * @deprecated 1.9 Use \ElggSite::getEntities()
 */
function get_site_objects($site_guid, $subtype = "", $limit = 10, $offset = 0) {
	elgg_deprecated_notice('get_site_objects() is deprecated. Use \ElggSite::getEntities()', 1.9);
	$site_guid = (int)$site_guid;
	$limit = (int)$limit;
	$offset = (int)$offset;

	return elgg_get_entities_from_relationship(array(
		'relationship' => 'member_of_site',
		'relationship_guid' => $site_guid,
		'inverse_relationship' => true,
		'type' => 'object',
		'subtype' => $subtype,
		'limit' => $limit,
		'offset' => $offset
	));
}

/**
 * Get the sites this object is part of
 *
 * @param int $object_guid The object's GUID
 * @param int $limit       Number of results to return
 * @param int $offset      Any indexing offset
 *
 * @return array On success, an array of \ElggSites
 * @deprecated 1.9 Use \ElggEntity::getSites()
 */
function get_object_sites($object_guid, $limit = 10, $offset = 0) {
	elgg_deprecated_notice('get_object_sites() is deprecated. Use \ElggEntity::getSites()', 1.9);
	$object_guid = (int)$object_guid;
	$limit = (int)$limit;
	$offset = (int)$offset;

	return elgg_get_entities_from_relationship(array(
		'relationship' => 'member_of_site',
		'relationship_guid' => $object_guid,
		'type' => 'site',
		'limit' => $limit,
		'offset' => $offset,
	));
}

/**
 * Get the sites this user is part of
 *
 * @param int $user_guid The user's GUID
 * @param int $limit     Number of results to return
 * @param int $offset    Any indexing offset
 *
 * @return \ElggSite[]|false On success, an array of \ElggSites
 * @deprecated 1.9 Use \ElggEntity::getSites()
 */
function get_user_sites($user_guid, $limit = 10, $offset = 0) {
	elgg_deprecated_notice('get_user_sites() is deprecated. Use \ElggEntity::getSites()', 1.9);
	$user_guid = (int)$user_guid;
	$limit = (int)$limit;
	$offset = (int)$offset;

	return elgg_get_entities_from_relationship(array(
		'site_guids' => ELGG_ENTITIES_ANY_VALUE,
		'relationship' => 'member_of_site',
		'relationship_guid' => $user_guid,
		'inverse_relationship' => false,
		'type' => 'site',
		'limit' => $limit,
		'offset' => $offset,
	));
}

/**
 * Determines whether or not the specified user can edit the specified piece of extender
 *
 * @param int    $extender_id The ID of the piece of extender
 * @param string $type        'metadata' or 'annotation'
 * @param int    $user_guid   The GUID of the user
 *
 * @return bool
 * @deprecated 1.9 Use the appropriate canEdit() method on metadata or annotations
 */
function can_edit_extender($extender_id, $type, $user_guid = 0) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \ElggExtender::canEdit()', 1.9);

	// Since Elgg 1.0, Elgg has returned false from can_edit_extender()
	// if no user was logged in. This breaks the access override so we add this
	// special check here.
	if (!elgg_check_access_overrides($user_guid)) {
		if (!elgg_is_logged_in()) {
			return false;
		}
	}

	$user_guid = (int)$user_guid;
	$user = get_user($user_guid);
	if (!$user) {
		$user = elgg_get_logged_in_user_entity();
		$user_guid = elgg_get_logged_in_user_guid();
	}

	$functionname = "elgg_get_{$type}_from_id";
	if (is_callable($functionname)) {
		$extender = call_user_func($functionname, $extender_id);
	} else {
		return false;
	}

	if (!($extender instanceof \ElggExtender)) {
		return false;
	}
	/* @var \ElggExtender $extender */

	// If the owner is the specified user, great! They can edit.
	if ($extender->getOwnerGUID() == $user_guid) {
		return true;
	}

	// If the user can edit the entity this is attached to, great! They can edit.
	$entity = $extender->getEntity();
	if ($entity->canEdit($user_guid)) {
		return true;
	}

	// Trigger plugin hook - note that $user may be null
	$params = array('entity' => $entity, 'user' => $user);
	return elgg_trigger_plugin_hook('permissions_check', $type, $params, false);
}

/**
 * The algorithm working out the size of font based on the number of tags.
 * This is quick and dirty.
 *
 * @param int $min            Min size
 * @param int $max            Max size
 * @param int $number_of_tags The number of tags
 * @param int $buckets        The number of buckets
 *
 * @return int
 * @access private
 * @deprecated 1.9
 */
function calculate_tag_size($min, $max, $number_of_tags, $buckets = 6) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
	$delta = (($max - $min) / $buckets);
	$thresholds = array();

	for ($n = 1; $n <= $buckets; $n++) {
		$thresholds[$n - 1] = ($min + $n) * $delta;
	}

	// Correction
	if ($thresholds[$buckets - 1] > $max) {
		$thresholds[$buckets - 1] = $max;
	}

	$size = 0;
	for ($n = 0; $n < count($thresholds); $n++) {
		if ($number_of_tags >= $thresholds[$n]) {
			$size = $n;
		}
	}

	return $size;
}

/**
 * This function generates an array of tags with a weighting.
 *
 * @param array $tags    The array of tags.
 * @param int   $buckets The number of buckets
 *
 * @return array An associated array of tags with a weighting, this can then be mapped to a display class.
 * @access private
 * @deprecated 1.9
 */
function generate_tag_cloud(array $tags, $buckets = 6) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
	$cloud = array();

	$min = 65535;
	$max = 0;

	foreach ($tags as $tag) {
		$cloud[$tag]++;

		if ($cloud[$tag] > $max) {
			$max = $cloud[$tag];
		}

		if ($cloud[$tag] < $min) {
			$min = $cloud[$tag];
		}
	}

	foreach ($cloud as $k => $v) {
		$cloud[$k] = calculate_tag_size($min, $max, $v, $buckets);
	}

	return $cloud;
}

/**
 * Invalidate the metadata cache based on options passed to various *_metadata functions
 *
 * @param string $action  Action performed on metadata. "delete", "disable", or "enable"
 * @param array  $options Options passed to elgg_(delete|disable|enable)_metadata
 * @return void
 * @deprecated 1.9
 */
function elgg_invalidate_metadata_cache($action, array $options) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
	_elgg_invalidate_metadata_cache($action, $options);
}

global $METASTRINGS_DEADNAME_CACHE;
$METASTRINGS_DEADNAME_CACHE = array();

/**
 * Return the meta string id for a given tag, or false.
 *
 * @param string $string         The value to store
 * @param bool   $case_sensitive Do we want to make the query case sensitive?
 *                               If not there may be more than one result
 *
 * @return int|array|false meta   string id, array of ids or false if none found
 * @deprecated 1.9 Use elgg_get_metastring_id()
 */
function get_metastring_id($string, $case_sensitive = TRUE) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use elgg_get_metastring_id()', 1.9);
	global $CONFIG, $METASTRINGS_CACHE, $METASTRINGS_DEADNAME_CACHE;

	$string = sanitise_string($string);

	// caching doesn't work for case insensitive searches
	if ($case_sensitive) {
		$result = array_search($string, $METASTRINGS_CACHE, true);

		if ($result !== false) {
			return $result;
		}

		// See if we have previously looked for this and found nothing
		if (in_array($string, $METASTRINGS_DEADNAME_CACHE, true)) {
			return false;
		}

		// Experimental memcache
		$msfc = null;
		static $metastrings_memcache;
		if ((!$metastrings_memcache) && (is_memcache_available())) {
			$metastrings_memcache = new \ElggMemcache('metastrings_memcache');
		}
		if ($metastrings_memcache) {
			$msfc = $metastrings_memcache->load($string);
		}
		if ($msfc) {
			return $msfc;
		}
	}

	// Case sensitive
	if ($case_sensitive) {
		$query = "SELECT * from {$CONFIG->dbprefix}metastrings where string= BINARY '$string' limit 1";
	} else {
		$query = "SELECT * from {$CONFIG->dbprefix}metastrings where string = '$string'";
	}

	$row = FALSE;
	$metaStrings = get_data($query);
	if (is_array($metaStrings)) {
		if (sizeof($metaStrings) > 1) {
			$ids = array();
			foreach ($metaStrings as $metaString) {
				$ids[] = $metaString->id;
			}
			return $ids;
		} else if (isset($metaStrings[0])) {
			$row = $metaStrings[0];
		}
	}

	if ($row) {
		$METASTRINGS_CACHE[$row->id] = $row->string; // Cache it

		// Attempt to memcache it if memcache is available
		if ($metastrings_memcache) {
			$metastrings_memcache->save($row->string, $row->id);
		}

		return $row->id;
	} else {
		$METASTRINGS_DEADNAME_CACHE[$string] = $string;
	}

	return false;
}

/**
 * Add a metastring.
 * It returns the id of the metastring. If it does not exist, it will be created.
 *
 * @param string $string         The value (whatever that is) to be stored
 * @param bool   $case_sensitive Do we want to make the query case sensitive?
 *
 * @return mixed Integer tag or false.
 * @deprecated 1.9 Use elgg_get_metastring_id()
 */
function add_metastring($string, $case_sensitive = true) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use elgg_get_metastring_id()', 1.9);
	global $CONFIG, $METASTRINGS_CACHE, $METASTRINGS_DEADNAME_CACHE;

	$sanstring = sanitise_string($string);

	$id = get_metastring_id($string, $case_sensitive);
	if ($id) {
		return $id;
	}

	$result = insert_data("INSERT into {$CONFIG->dbprefix}metastrings (string) values ('$sanstring')");
	if ($result) {
		$METASTRINGS_CACHE[$result] = $string;
		if (isset($METASTRINGS_DEADNAME_CACHE[$string])) {
			unset($METASTRINGS_DEADNAME_CACHE[$string]);
		}
	}

	return $result;
}

/**
 * When given an ID, returns the corresponding metastring
 *
 * @param int $id Metastring ID
 *
 * @return string Metastring
 * @deprecated 1.9
 */
function get_metastring($id) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated.', 1.9);
	global $CONFIG, $METASTRINGS_CACHE;

	$id = (int) $id;

	if (isset($METASTRINGS_CACHE[$id])) {
		return $METASTRINGS_CACHE[$id];
	}

	$row = get_data_row("SELECT * from {$CONFIG->dbprefix}metastrings where id='$id' limit 1");
	if ($row) {
		$METASTRINGS_CACHE[$id] = $row->string;
		return $row->string;
	}

	return false;
}

/**
 * Obtains a list of objects owned by a user's friends
 *
 * @param int    $user_guid The GUID of the user to get the friends of
 * @param string $subtype   Optionally, the subtype of objects
 * @param int    $limit     The number of results to return (default 10)
 * @param int    $offset    Indexing offset, if any
 * @param int    $timelower The earliest time the entity can have been created. Default: all
 * @param int    $timeupper The latest time the entity can have been created. Default: all
 *
 * @return \ElggObject[]|false An array of \ElggObjects or false, depending on success
 * @deprecated 1.9 Use elgg_get_entities_from_relationship()
 */
function get_user_friends_objects($user_guid, $subtype = ELGG_ENTITIES_ANY_VALUE, $limit = 10,
	$offset = 0, $timelower = 0, $timeupper = 0) {

	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use elgg_get_entities_from_relationship()', 1.9);
	return elgg_get_entities_from_relationship(array(
		'type' => 'object',
		'subtype' => $subtype,
		'limit' => $limit,
		'offset' => $offset,
		'created_time_lower' => $timelower,
		'created_time_upper' => $timeupper,
		'relationship' => 'friend',
		'relationship_guid' => $user_guid,
		'relationship_join_on' => 'container_guid',
	));
}

/**
 * Counts the number of objects owned by a user's friends
 *
 * @param int    $user_guid The GUID of the user to get the friends of
 * @param string $subtype   Optionally, the subtype of objects
 * @param int    $timelower The earliest time the entity can have been created. Default: all
 * @param int    $timeupper The latest time the entity can have been created. Default: all
 *
 * @return int The number of objects
 * @deprecated 1.9 Use elgg_get_entities_from_relationship()
 */
function count_user_friends_objects($user_guid, $subtype = ELGG_ENTITIES_ANY_VALUE,
$timelower = 0, $timeupper = 0) {

	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use elgg_get_entities_from_relationship()', 1.9);
	return elgg_get_entities_from_relationship(array(
		'type' => 'object',
		'subtype' => $subtype,
		'created_time_lower' => $timelower,
		'created_time_upper' => $timeupper,
		'relationship' => 'friend',
		'relationship_guid' => $user_guid,
		'relationship_join_on' => 'container_guid',
		'count' => true,
	));
}

/**
 * Displays a list of a user's friends' objects of a particular subtype, with navigation.
 *
 * @see elgg_view_entity_list
 *
 * @param int    $user_guid      The GUID of the user
 * @param string $subtype        The object subtype
 * @param int    $limit          The number of entities to display on a page
 * @param bool   $full_view      Whether or not to display the full view (default: true)
 * @param bool   $listtypetoggle Whether or not to allow you to flip to gallery mode (default: true)
 * @param bool   $pagination     Whether to display pagination (default: true)
 * @param int    $timelower      The earliest time the entity can have been created. Default: all
 * @param int    $timeupper      The latest time the entity can have been created. Default: all
 *
 * @return string
 * @deprecated 1.9 Use elgg_list_entities_from_relationship()
 */
function list_user_friends_objects($user_guid, $subtype = "", $limit = 10, $full_view = true,
	$listtypetoggle = true, $pagination = true, $timelower = 0, $timeupper = 0) {

	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use elgg_list_entities_from_relationship()', 1.9);
	return elgg_list_entities_from_relationship(array(
		'type' => 'object',
		'subtype' => $subtype,
		'limit' => $limit,
		'created_time_lower' => $timelower,
		'created_time_upper' => $timeupper,
		'full_view' => $full_view,
		'list_type_toggle' => $listtypetoggle,
		'pagination' => $pagination,
		'relationship' => 'friend',
		'relationship_guid' => $user_guid,
		'relationship_join_on' => 'container_guid',
	));
}

/**
 * Encode a location into a latitude and longitude, caching the result.
 *
 * Works by triggering the 'geocode' 'location' plugin
 * hook, and requires a geocoding plugin to be installed.
 *
 * @param string $location The location, e.g. "London", or "24 Foobar Street, Gotham City"
 * @return string|false
 * @deprecated 1.9.0 See geolocation plugin on github
 */
function elgg_geocode_location($location) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. See geolocation plugin on github', 1.9);
	global $CONFIG;

	if (is_array($location)) {
		return false;
	}

	$location = sanitise_string($location);

	// Look for cached version
	$query = "SELECT * from {$CONFIG->dbprefix}geocode_cache WHERE location='$location'";
	$cached_location = get_data_row($query);

	if ($cached_location) {
		return array('lat' => $cached_location->lat, 'long' => $cached_location->long);
	}

	// Trigger geocode event if not cached
	$return = false;
	$return = elgg_trigger_plugin_hook('geocode', 'location', array('location' => $location), $return);

	// If returned, cache and return value
	if (($return) && (is_array($return))) {
		$lat = (float)$return['lat'];
		$long = (float)$return['long'];

		// Put into cache at the end of the page since we don't really care that much
		$query = "INSERT DELAYED INTO {$CONFIG->dbprefix}geocode_cache "
			. " (location, lat, `long`) VALUES ('$location', '{$lat}', '{$long}')"
			. " ON DUPLICATE KEY UPDATE lat='{$lat}', `long`='{$long}'";
		execute_delayed_write_query($query);
	}

	return $return;
}

/**
 * Return entities within a given geographic area.
 *
 * Also accepts all options available to elgg_get_entities().
 *
 * @see elgg_get_entities
 *
 * @param array $options Array in format:
 *
 * 	latitude => FLOAT Latitude of the location
 *
 * 	longitude => FLOAT Longitude of the location
 *
 *  distance => FLOAT/ARR (
 *						latitude => float,
 *						longitude => float,
 *					)
 *					The distance in degrees that determines the search box. A
 *					single float will result in a square in degrees.
 * @warning The Earth is round.
 *
 * @see \ElggEntity::setLatLong()
 *
 * @return mixed If count, int. If not count, array. false on errors.
 * @since 1.8.0
 * @deprecated 1.9.0 See geolocation plugin on github
 */
function elgg_get_entities_from_location(array $options = array()) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. See geolocation plugin on github', 1.9);
	global $CONFIG;

	if (!isset($options['latitude']) || !isset($options['longitude']) ||
		!isset($options['distance'])) {
		return false;
	}

	if (!is_array($options['distance'])) {
		$lat_distance = (float)$options['distance'];
		$long_distance = (float)$options['distance'];
	} else {
		$lat_distance = (float)$options['distance']['latitude'];
		$long_distance = (float)$options['distance']['longitude'];
	}

	$lat = (float)$options['latitude'];
	$long = (float)$options['longitude'];
	$lat_min = $lat - $lat_distance;
	$lat_max = $lat + $lat_distance;
	$long_min = $long - $long_distance;
	$long_max = $long + $long_distance;

	$wheres = array();
	$wheres[] = "lat_name.string='geo:lat'";
	$wheres[] = "lat_value.string >= $lat_min";
	$wheres[] = "lat_value.string <= $lat_max";
	$wheres[] = "lon_name.string='geo:long'";
	$wheres[] = "lon_value.string >= $long_min";
	$wheres[] = "lon_value.string <= $long_max";

	$joins = array();
	$joins[] = "JOIN {$CONFIG->dbprefix}metadata lat on e.guid=lat.entity_guid";
	$joins[] = "JOIN {$CONFIG->dbprefix}metastrings lat_name on lat.name_id=lat_name.id";
	$joins[] = "JOIN {$CONFIG->dbprefix}metastrings lat_value on lat.value_id=lat_value.id";
	$joins[] = "JOIN {$CONFIG->dbprefix}metadata lon on e.guid=lon.entity_guid";
	$joins[] = "JOIN {$CONFIG->dbprefix}metastrings lon_name on lon.name_id=lon_name.id";
	$joins[] = "JOIN {$CONFIG->dbprefix}metastrings lon_value on lon.value_id=lon_value.id";

	// merge wheres to pass to get_entities()
	if (isset($options['wheres']) && !is_array($options['wheres'])) {
		$options['wheres'] = array($options['wheres']);
	} elseif (!isset($options['wheres'])) {
		$options['wheres'] = array();
	}
	$options['wheres'] = array_merge($options['wheres'], $wheres);

	// merge joins to pass to get_entities()
	if (isset($options['joins']) && !is_array($options['joins'])) {
		$options['joins'] = array($options['joins']);
	} elseif (!isset($options['joins'])) {
		$options['joins'] = array();
	}
	$options['joins'] = array_merge($options['joins'], $joins);

	return elgg_get_entities_from_relationship($options);
}

/**
 * Returns a viewable list of entities from location
 *
 * @param array $options Options array
 *
 * @see elgg_list_entities()
 * @see elgg_get_entities_from_location()
 *
 * @return string The viewable list of entities
 * @since 1.8.0
 * @deprecated 1.9.0 See geolocation plugin on github
 */
function elgg_list_entities_from_location(array $options = array()) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. See geolocation plugin on github', 1.9);
	return elgg_list_entities($options, 'elgg_get_entities_from_location');
}

// Some distances in degrees (approximate)
// @todo huh? see warning on elgg_get_entities_from_location()
// @deprecated 1.9.0
define("MILE", 0.01515);
define("KILOMETER", 0.00932);

/**
 * Get the current Elgg version information
 *
 * @param bool $humanreadable Whether to return a human readable version (default: false)
 *
 * @return string|false Depending on success
 * @deprecated 1.9 Use elgg_get_version()
 */
function get_version($humanreadable = false) {
	elgg_deprecated_notice('get_version() has been deprecated by elgg_get_version()', 1.9);
	return elgg_get_version($humanreadable);
}

/**
 * Sanitise a string for database use, but with the option of escaping extra characters.
 *
 * @param string $string           The string to sanitise
 * @param string $extra_escapeable Extra characters to escape with '\\'
 *
 * @return string The escaped string
 * @deprecated 1.9
 */
function sanitise_string_special($string, $extra_escapeable = '') {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated.', 1.9);
	$string = sanitise_string($string);

	for ($n = 0; $n < strlen($extra_escapeable); $n++) {
		$string = str_replace($extra_escapeable[$n], "\\" . $extra_escapeable[$n], $string);
	}

	return $string;
}

/**
 * Establish database connections
 *
 * If the configuration has been set up for multiple read/write databases, set those
 * links up separately; otherwise just create the one database link.
 *
 * @return void
 * @access private
 * @deprecated 1.9
 */
function setup_db_connections() {
	elgg_deprecated_notice(__FUNCTION__ . ' is a private function and should not be used.', 1.9);
	_elgg_services()->db->setupConnections();
}

/**
 * Returns (if required, also creates) a database link resource.
 *
 * Database link resources are stored in the {@link $dblink} global.  These
 * resources are created by {@link setup_db_connections()}, which is called if
 * no links exist.
 *
 * @param string $dblinktype The type of link we want: "read", "write" or "readwrite".
 *
 * @return resource Database link
 * @access private
 * @deprecated 1.9
 */
function get_db_link($dblinktype) {
	elgg_deprecated_notice(__FUNCTION__ . ' is a private function and should not be used.', 1.9);
	return _elgg_services()->db->getLink($dblinktype);
}

/**
 * Optimize a table.
 *
 * Executes an OPTIMIZE TABLE query on $table.  Useful after large DB changes.
 *
 * @param string $table The name of the table to optimise
 *
 * @return bool
 * @access private
 * @deprecated 1.9
 */
function optimize_table($table) {
	elgg_deprecated_notice(__FUNCTION__ . ' is a private function and should not be used.', 1.9);
	$table = sanitise_string($table);
	return _elgg_services()->db->updateData("OPTIMIZE TABLE $table");
}

/**
 * Return tables matching the database prefix {@link $CONFIG->dbprefix}% in the currently
 * selected database.
 *
 * @return array|false List of tables or false on failure
 * @static array $tables Tables found matching the database prefix
 * @access private
 * @deprecated 1.9
 */
function get_db_tables() {
	elgg_deprecated_notice(__FUNCTION__ . ' is a private function and should not be used.', 1.9);
	static $tables;

	if (isset($tables)) {
		return $tables;
	}

	$table_prefix = elgg_get_config('dbprefix');
	$result = get_data("SHOW TABLES LIKE '$table_prefix%'");

	$tables = array();
	if (is_array($result) && !empty($result)) {
		foreach ($result as $row) {
			$row = (array) $row;
			if (is_array($row) && !empty($row)) {
				foreach ($row as $element) {
					$tables[] = $element;
				}
			}
		}
	}

	return $tables;
}

/**
 * Get the last database error for a particular database link
 *
 * @param resource $dblink The DB link
 *
 * @return string Database error message
 * @access private
 * @deprecated 1.9
 */
function get_db_error($dblink) {
	elgg_deprecated_notice(__FUNCTION__ . ' is a private function and should not be used.', 1.9);
	return mysql_error($dblink);
}

/**
 * Queue a query for execution upon shutdown.
 *
 * You can specify a handler function if you care about the result. This function will accept
 * the raw result from {@link mysql_query()}.
 *
 * @param string   $query   The query to execute
 * @param resource $dblink  The database link to use or the link type (read | write)
 * @param string   $handler A callback function to pass the results array to
 *
 * @return boolean Whether successful.
 * @deprecated 1.9 Use execute_delayed_write_query() or execute_delayed_read_query()
 */
function execute_delayed_query($query, $dblink, $handler = "") {
	elgg_deprecated_notice("execute_delayed_query() has been deprecated", 1.9);
	return _elgg_services()->db->registerDelayedQuery($query, $dblink, $handler);
}

/**
 * Return a timestamp for the start of a given day (defaults today).
 *
 * @param int $day   Day
 * @param int $month Month
 * @param int $year  Year
 *
 * @return int
 * @access private
 * @deprecated 1.9
 */
function get_day_start($day = null, $month = null, $year = null) {
	elgg_deprecated_notice('get_day_start() has been deprecated', 1.9);
	return mktime(0, 0, 0, $month, $day, $year);
}

/**
 * Return a timestamp for the end of a given day (defaults today).
 *
 * @param int $day   Day
 * @param int $month Month
 * @param int $year  Year
 *
 * @return int
 * @access private
 * @deprecated 1.9
 */
function get_day_end($day = null, $month = null, $year = null) {
	elgg_deprecated_notice('get_day_end() has been deprecated', 1.9);
	return mktime(23, 59, 59, $month, $day, $year);
}

/**
 * Return the notable entities for a given time period.
 *
 * @todo this function also accepts an array(type => subtypes) for 3rd arg. Should we document this?
 *
 * @param int     $start_time     The start time as a unix timestamp.
 * @param int     $end_time       The end time as a unix timestamp.
 * @param string  $type           The type of entity (eg "user", "object" etc)
 * @param string  $subtype        The arbitrary subtype of the entity
 * @param int     $owner_guid     The GUID of the owning user
 * @param string  $order_by       The field to order by; by default, time_created desc
 * @param int     $limit          The number of entities to return; 10 by default
 * @param int     $offset         The indexing offset, 0 by default
 * @param boolean $count          Set to true to get a count instead of entities. Defaults to false.
 * @param int     $site_guid      Site to get entities for. Default 0 = current site. -1 = any.
 * @param mixed   $container_guid Container or containers to get entities from (default: any).
 *
 * @return array|false
 * @access private
 * @deprecated 1.9
 */
function get_notable_entities($start_time, $end_time, $type = "", $subtype = "", $owner_guid = 0,
$order_by = "asc", $limit = 10, $offset = 0, $count = false, $site_guid = 0,
$container_guid = null) {
	elgg_deprecated_notice('get_notable_entities() has been deprecated', 1.9);
	global $CONFIG;

	if ($subtype === false || $subtype === null || $subtype === 0) {
		return false;
	}

	$start_time = (int)$start_time;
	$end_time = (int)$end_time;
	$order_by = sanitise_string($order_by);
	$limit = (int)$limit;
	$offset = (int)$offset;
	$site_guid = (int) $site_guid;
	if ($site_guid == 0) {
		$site_guid = $CONFIG->site_guid;
	}

	$where = array();

	if (is_array($type)) {
		$tempwhere = "";
		if (sizeof($type)) {
			foreach ($type as $typekey => $subtypearray) {
				foreach ($subtypearray as $subtypeval) {
					$typekey = sanitise_string($typekey);
					if (!empty($subtypeval)) {
						$subtypeval = (int) get_subtype_id($typekey, $subtypeval);
					} else {
						$subtypeval = 0;
					}
					if (!empty($tempwhere)) {
						$tempwhere .= " or ";
					}
					$tempwhere .= "(e.type = '{$typekey}' and e.subtype = {$subtypeval})";
				}
			}
		}
		if (!empty($tempwhere)) {
			$where[] = "({$tempwhere})";
		}
	} else {
		$type = sanitise_string($type);
		$subtype = get_subtype_id($type, $subtype);

		if ($type != "") {
			$where[] = "e.type='$type'";
		}

		if ($subtype !== "") {
			$where[] = "e.subtype=$subtype";
		}
	}

	if ($owner_guid != "") {
		if (!is_array($owner_guid)) {
			$owner_array = array($owner_guid);
			$owner_guid = (int) $owner_guid;
			$where[] = "e.owner_guid = '$owner_guid'";
		} else if (sizeof($owner_guid) > 0) {
			$owner_array = array_map('sanitise_int', $owner_guid);
			// Cast every element to the owner_guid array to int
			$owner_guid = implode(",", $owner_guid);
			$where[] = "e.owner_guid in ({$owner_guid})";
		}
		if (is_null($container_guid)) {
			$container_guid = $owner_array;
		}
	}

	if ($site_guid > 0) {
		$where[] = "e.site_guid = {$site_guid}";
	}

	if (!is_null($container_guid)) {
		if (is_array($container_guid)) {
			foreach ($container_guid as $key => $val) {
				$container_guid[$key] = (int) $val;
			}
			$where[] = "e.container_guid in (" . implode(",", $container_guid) . ")";
		} else {
			$container_guid = (int) $container_guid;
			$where[] = "e.container_guid = {$container_guid}";
		}
	}

	// Add the calendar stuff
	$cal_join = "
		JOIN {$CONFIG->dbprefix}metadata cal_start on e.guid=cal_start.entity_guid
		JOIN {$CONFIG->dbprefix}metastrings cal_start_name on cal_start.name_id=cal_start_name.id
		JOIN {$CONFIG->dbprefix}metastrings cal_start_value on cal_start.value_id=cal_start_value.id

		JOIN {$CONFIG->dbprefix}metadata cal_end on e.guid=cal_end.entity_guid
		JOIN {$CONFIG->dbprefix}metastrings cal_end_name on cal_end.name_id=cal_end_name.id
		JOIN {$CONFIG->dbprefix}metastrings cal_end_value on cal_end.value_id=cal_end_value.id
	";
	$where[] = "cal_start_name.string='calendar_start'";
	$where[] = "cal_start_value.string>=$start_time";
	$where[] = "cal_end_name.string='calendar_end'";
	$where[] = "cal_end_value.string <= $end_time";


	if (!$count) {
		$query = "SELECT e.* from {$CONFIG->dbprefix}entities e $cal_join where ";
	} else {
		$query = "SELECT count(e.guid) as total from {$CONFIG->dbprefix}entities e $cal_join where ";
	}
	foreach ($where as $w) {
		$query .= " $w and ";
	}

	$query .= _elgg_get_access_where_sql();

	if (!$count) {
		$query .= " order by n.calendar_start $order_by";
		// Add order and limit
		if ($limit) {
			$query .= " limit $offset, $limit";
		}
		$dt = get_data($query, "entity_row_to_elggstar");

		return $dt;
	} else {
		$total = get_data_row($query);
		return $total->total;
	}
}

/**
 * Return the notable entities for a given time period based on an item of metadata.
 *
 * @param int    $start_time     The start time as a unix timestamp.
 * @param int    $end_time       The end time as a unix timestamp.
 * @param mixed  $meta_name      Metadata name
 * @param mixed  $meta_value     Metadata value
 * @param string $entity_type    The type of entity to look for, eg 'site' or 'object'
 * @param string $entity_subtype The subtype of the entity.
 * @param int    $owner_guid     Owner GUID
 * @param int    $limit          Limit
 * @param int    $offset         Offset
 * @param string $order_by       Optional ordering.
 * @param int    $site_guid      Site to get entities for. Default 0 = current site. -1 = any.
 * @param bool   $count          If true, returns count instead of entities. (Default: false)
 *
 * @return int|array A list of entities, or a count if $count is set to true
 * @access private
 * @deprecated 1.9
 */
function get_notable_entities_from_metadata($start_time, $end_time, $meta_name, $meta_value = "",
$entity_type = "", $entity_subtype = "", $owner_guid = 0, $limit = 10, $offset = 0, $order_by = "",
$site_guid = 0, $count = false) {
	elgg_deprecated_notice('get_notable_entities_from_metadata() has been deprecated', 1.9);

	global $CONFIG;

	$meta_n = get_metastring_id($meta_name);
	$meta_v = get_metastring_id($meta_value);

	$start_time = (int)$start_time;
	$end_time = (int)$end_time;
	$entity_type = sanitise_string($entity_type);
	$entity_subtype = get_subtype_id($entity_type, $entity_subtype);
	$limit = (int)$limit;
	$offset = (int)$offset;
	if ($order_by == "") {
		$order_by = "e.time_created desc";
	}
	$order_by = sanitise_string($order_by);
	$site_guid = (int) $site_guid;
	if ((is_array($owner_guid) && (count($owner_guid)))) {
		foreach ($owner_guid as $key => $guid) {
			$owner_guid[$key] = (int) $guid;
		}
	} else {
		$owner_guid = (int) $owner_guid;
	}

	if ($site_guid == 0) {
		$site_guid = $CONFIG->site_guid;
	}

	//$access = get_access_list();

	$where = array();

	if ($entity_type != "") {
		$where[] = "e.type='$entity_type'";
	}

	if ($entity_subtype) {
		$where[] = "e.subtype=$entity_subtype";
	}

	if ($meta_name != "") {
		$where[] = "m.name_id='$meta_n'";
	}

	if ($meta_value != "") {
		$where[] = "m.value_id='$meta_v'";
	}

	if ($site_guid > 0) {
		$where[] = "e.site_guid = {$site_guid}";
	}

	if (is_array($owner_guid)) {
		$where[] = "e.container_guid in (" . implode(",", $owner_guid) . ")";
	} else if ($owner_guid > 0) {
		$where[] = "e.container_guid = {$owner_guid}";
	}

	// Add the calendar stuff
	$cal_join = "
		JOIN {$CONFIG->dbprefix}metadata cal_start on e.guid=cal_start.entity_guid
		JOIN {$CONFIG->dbprefix}metastrings cal_start_name on cal_start.name_id=cal_start_name.id
		JOIN {$CONFIG->dbprefix}metastrings cal_start_value on cal_start.value_id=cal_start_value.id

		JOIN {$CONFIG->dbprefix}metadata cal_end on e.guid=cal_end.entity_guid
		JOIN {$CONFIG->dbprefix}metastrings cal_end_name on cal_end.name_id=cal_end_name.id
		JOIN {$CONFIG->dbprefix}metastrings cal_end_value on cal_end.value_id=cal_end_value.id
	";

	$where[] = "cal_start_name.string='calendar_start'";
	$where[] = "cal_start_value.string>=$start_time";
	$where[] = "cal_end_name.string='calendar_end'";
	$where[] = "cal_end_value.string <= $end_time";

	if (!$count) {
		$query = "SELECT distinct e.* ";
	} else {
		$query = "SELECT count(distinct e.guid) as total ";
	}

	$query .= "from {$CONFIG->dbprefix}entities e"
	. " JOIN {$CONFIG->dbprefix}metadata m on e.guid = m.entity_guid $cal_join where";

	foreach ($where as $w) {
		$query .= " $w and ";
	}

	// Add access controls
	$query .= _elgg_get_access_where_sql(array('table_alias' => 'e'));
	$query .= ' and ' . _elgg_get_access_where_sql(array('table_alias' => "m"));

	if (!$count) {
		// Add order and limit
		$query .= " order by $order_by limit $offset, $limit";
		return get_data($query, "entity_row_to_elggstar");
	} else {
		if ($row = get_data_row($query)) {
			return $row->total;
		}
	}

	return false;
}

/**
 * Return the notable entities for a given time period based on their relationship.
 *
 * @param int     $start_time           The start time as a unix timestamp.
 * @param int     $end_time             The end time as a unix timestamp.
 * @param string  $relationship         The relationship eg "friends_of"
 * @param int     $relationship_guid    The guid of the entity to use query
 * @param bool    $inverse_relationship Reverse the normal function of the query to say
 *                                      "give me all entities for whom $relationship_guid is a
 *                                      $relationship of"
 * @param string  $type                 Entity type
 * @param string  $subtype              Entity subtype
 * @param int     $owner_guid           Owner GUID
 * @param string  $order_by             Optional Order by
 * @param int     $limit                Limit
 * @param int     $offset               Offset
 * @param boolean $count                If true returns a count of entities (default false)
 * @param int     $site_guid            Site to get entities for. Default 0 = current site. -1 = any
 *
 * @return array|int|false An array of entities, or the number of entities, or false on failure
 * @access private
 * @deprecated 1.9
 */
function get_noteable_entities_from_relationship($start_time, $end_time, $relationship,
$relationship_guid, $inverse_relationship = false, $type = "", $subtype = "", $owner_guid = 0,
$order_by = "", $limit = 10, $offset = 0, $count = false, $site_guid = 0) {
	elgg_deprecated_notice('get_noteable_entities_from_relationship() has been deprecated', 1.9);

	global $CONFIG;

	$start_time = (int)$start_time;
	$end_time = (int)$end_time;
	$relationship = sanitise_string($relationship);
	$relationship_guid = (int)$relationship_guid;
	$inverse_relationship = (bool)$inverse_relationship;
	$type = sanitise_string($type);
	$subtype = get_subtype_id($type, $subtype);
	$owner_guid = (int)$owner_guid;
	if ($order_by == "") {
		$order_by = "time_created desc";
	}
	$order_by = sanitise_string($order_by);
	$limit = (int)$limit;
	$offset = (int)$offset;
	$site_guid = (int) $site_guid;
	if ($site_guid == 0) {
		$site_guid = $CONFIG->site_guid;
	}

	//$access = get_access_list();

	$where = array();

	if ($relationship != "") {
		$where[] = "r.relationship='$relationship'";
	}
	if ($relationship_guid) {
		$where[] = $inverse_relationship ?
			"r.guid_two='$relationship_guid'" : "r.guid_one='$relationship_guid'";
	}
	if ($type != "") {
		$where[] = "e.type='$type'";
	}
	if ($subtype) {
		$where[] = "e.subtype=$subtype";
	}
	if ($owner_guid != "") {
		$where[] = "e.container_guid='$owner_guid'";
	}
	if ($site_guid > 0) {
		$where[] = "e.site_guid = {$site_guid}";
	}

	// Add the calendar stuff
	$cal_join = "
		JOIN {$CONFIG->dbprefix}metadata cal_start on e.guid=cal_start.entity_guid
		JOIN {$CONFIG->dbprefix}metastrings cal_start_name on cal_start.name_id=cal_start_name.id
		JOIN {$CONFIG->dbprefix}metastrings cal_start_value on cal_start.value_id=cal_start_value.id

		JOIN {$CONFIG->dbprefix}metadata cal_end on e.guid=cal_end.entity_guid
		JOIN {$CONFIG->dbprefix}metastrings cal_end_name on cal_end.name_id=cal_end_name.id
		JOIN {$CONFIG->dbprefix}metastrings cal_end_value on cal_end.value_id=cal_end_value.id
	";
	$where[] = "cal_start_name.string='calendar_start'";
	$where[] = "cal_start_value.string>=$start_time";
	$where[] = "cal_end_name.string='calendar_end'";
	$where[] = "cal_end_value.string <= $end_time";

	// Select what we're joining based on the options
	$joinon = "e.guid = r.guid_one";
	if (!$inverse_relationship) {
		$joinon = "e.guid = r.guid_two";
	}

	if ($count) {
		$query = "SELECT count(distinct e.guid) as total ";
	} else {
		$query = "SELECT distinct e.* ";
	}
	$query .= " from {$CONFIG->dbprefix}entity_relationships r"
	. " JOIN {$CONFIG->dbprefix}entities e on $joinon $cal_join where ";

	foreach ($where as $w) {
		$query .= " $w and ";
	}
	// Add access controls
	$query .= _elgg_get_access_where_sql();
	if (!$count) {
		$query .= " order by $order_by limit $offset, $limit"; // Add order and limit
		return get_data($query, "entity_row_to_elggstar");
	} else {
		if ($count = get_data_row($query)) {
			return $count->total;
		}
	}
	return false;
}

/**
 * Get all entities for today.
 *
 * @param string  $type           The type of entity (eg "user", "object" etc)
 * @param string  $subtype        The arbitrary subtype of the entity
 * @param int     $owner_guid     The GUID of the owning user
 * @param string  $order_by       The field to order by; by default, time_created desc
 * @param int     $limit          The number of entities to return; 10 by default
 * @param int     $offset         The indexing offset, 0 by default
 * @param boolean $count          If true returns a count of entities (default false)
 * @param int     $site_guid      Site to get entities for. Default 0 = current site. -1 = any
 * @param mixed   $container_guid Container(s) to get entities from (default: any).
 *
 * @return array|false
 * @access private
 * @deprecated 1.9
 */
function get_todays_entities($type = "", $subtype = "", $owner_guid = 0, $order_by = "",
$limit = 10, $offset = 0, $count = false, $site_guid = 0, $container_guid = null) {
	elgg_deprecated_notice('get_todays_entities() has been deprecated', 1.9);

	$day_start = get_day_start();
	$day_end = get_day_end();

	return get_notable_entities($day_start, $day_end, $type, $subtype, $owner_guid, $order_by,
		$limit, $offset, $count, $site_guid, $container_guid);
}

/**
 * Get entities for today from metadata.
 *
 * @param mixed  $meta_name      Metadata name
 * @param mixed  $meta_value     Metadata value
 * @param string $entity_type    The type of entity to look for, eg 'site' or 'object'
 * @param string $entity_subtype The subtype of the entity.
 * @param int    $owner_guid     Owner GUID
 * @param int    $limit          Limit
 * @param int    $offset         Offset
 * @param string $order_by       Optional ordering.
 * @param int    $site_guid      Site to get entities for. Default 0 = current site. -1 = any.
 * @param bool   $count          If true, returns count instead of entities. (Default: false)
 *
 * @return int|array A list of entities, or a count if $count is set to true
 * @access private
 * @deprecated 1.9
 */
function get_todays_entities_from_metadata($meta_name, $meta_value = "", $entity_type = "",
$entity_subtype = "", $owner_guid = 0, $limit = 10, $offset = 0, $order_by = "", $site_guid = 0,
$count = false) {
	elgg_deprecated_notice('get_todays_entities_from_metadata() has been deprecated', 1.9);

	$day_start = get_day_start();
	$day_end = get_day_end();

	return get_notable_entities_from_metadata($day_start, $day_end, $meta_name, $meta_value,
		$entity_type, $entity_subtype, $owner_guid, $limit, $offset, $order_by, $site_guid, $count);
}

/**
 * Get entities for today from a relationship
 *
 * @param string  $relationship         The relationship eg "friends_of"
 * @param int     $relationship_guid    The guid of the entity to use query
 * @param bool    $inverse_relationship Reverse the normal function of the query to say
 *                                      "give me all entities for whom $relationship_guid is a
 *                                      $relationship of"
 * @param string  $type                 Entity type
 * @param string  $subtype              Entity subtype
 * @param int     $owner_guid           Owner GUID
 * @param string  $order_by             Optional Order by
 * @param int     $limit                Limit
 * @param int     $offset               Offset
 * @param boolean $count                If true returns a count of entities (default false)
 * @param int     $site_guid            Site to get entities for. Default 0 = current site. -1 = any
 *
 * @return array|int|false An array of entities, or the number of entities, or false on failure
 * @access private
 * @deprecated 1.9
 */
function get_todays_entities_from_relationship($relationship, $relationship_guid,
$inverse_relationship = false, $type = "", $subtype = "", $owner_guid = 0,
$order_by = "", $limit = 10, $offset = 0, $count = false, $site_guid = 0) {
	elgg_deprecated_notice('get_todays_entities_from_relationship() has been deprecated', 1.9);

	$day_start = get_day_start();
	$day_end = get_day_end();

	return get_notable_entities_from_relationship($day_start, $day_end, $relationship,
		$relationship_guid,	$inverse_relationship, $type, $subtype, $owner_guid, $order_by,
		$limit, $offset, $count, $site_guid);
}

/**
 * Returns a viewable list of entities for a given time period.
 *
 * @see elgg_view_entity_list
 *
 * @param int     $start_time     The start time as a unix timestamp.
 * @param int     $end_time       The end time as a unix timestamp.
 * @param string  $type           The type of entity (eg "user", "object" etc)
 * @param string  $subtype        The arbitrary subtype of the entity
 * @param int     $owner_guid     The GUID of the owning user
 * @param int     $limit          The number of entities to return; 10 by default
 * @param boolean $fullview       Whether or not to display the full view (default: true)
 * @param boolean $listtypetoggle Whether or not to allow gallery view
 * @param boolean $navigation     Display pagination? Default: true
 *
 * @return string A viewable list of entities
 * @access private
 * @deprecated 1.9
 */
function list_notable_entities($start_time, $end_time, $type= "", $subtype = "", $owner_guid = 0,
$limit = 10, $fullview = true, $listtypetoggle = false, $navigation = true) {
	elgg_deprecated_notice('list_notable_entities() has been deprecated', 1.9);

	$offset = (int) get_input('offset');
	$count = get_notable_entities($start_time, $end_time, $type, $subtype,
		$owner_guid, "", $limit, $offset, true);

	$entities = get_notable_entities($start_time, $end_time, $type, $subtype,
		$owner_guid, "", $limit, $offset);

	return elgg_view_entity_list($entities, $count, $offset, $limit,
		$fullview, $listtypetoggle, $navigation);
}

/**
 * Return a list of today's entities.
 *
 * @see list_notable_entities
 *
 * @param string  $type           The type of entity (eg "user", "object" etc)
 * @param string  $subtype        The arbitrary subtype of the entity
 * @param int     $owner_guid     The GUID of the owning user
 * @param int     $limit          The number of entities to return; 10 by default
 * @param boolean $fullview       Whether or not to display the full view (default: true)
 * @param boolean $listtypetoggle Whether or not to allow gallery view
 * @param boolean $navigation     Display pagination? Default: true
 *
 * @return string A viewable list of entities
 * @access private
 * @deprecated 1.9
 */
function list_todays_entities($type= "", $subtype = "", $owner_guid = 0, $limit = 10,
$fullview = true, $listtypetoggle = false, $navigation = true) {
	elgg_deprecated_notice('list_todays_entities() has been deprecated', 1.9);

	$day_start = get_day_start();
	$day_end = get_day_end();

	return list_notable_entities($day_start, $day_end, $type, $subtype, $owner_guid, $limit,
		$fullview, $listtypetoggle, $navigation);
}

/**
 * Regenerates the simple cache.
 *
 * Not required any longer since cached files are created on demand.
 *
 * @warning This does not invalidate the cache, but actively rebuilds it.
 *
 * @param string $viewtype Optional viewtype to regenerate. Defaults to all valid viewtypes.
 *
 * @return void
 * @since 1.8.0
 * @deprecated 1.9 Use elgg_invalidate_simplecache()
 */
function elgg_regenerate_simplecache($viewtype = NULL) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated by elgg_invalidate_simplecache()', 1.9);
	elgg_invalidate_simplecache();
}

/**
 * @access private
 * @deprecated 1.9 Use elgg_get_system_cache()
 */
function elgg_get_filepath_cache() {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated by elgg_get_system_cache()', 1.9);
	return elgg_get_system_cache();
}
/**
 * @access private
 * @deprecated 1.9 Use elgg_reset_system_cache()
 */
function elgg_filepath_cache_reset() {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated by elgg_reset_system_cache()', 1.9);
	elgg_reset_system_cache();
}
/**
 * @access private
 * @deprecated 1.9 Use elgg_save_system_cache()
 */
function elgg_filepath_cache_save($type, $data) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated by elgg_save_system_cache()', 1.9);
	return elgg_save_system_cache($type, $data);
}
/**
 * @access private
 * @deprecated 1.9 Use elgg_load_system_cache()
 */
function elgg_filepath_cache_load($type) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated by elgg_load_system_cache()', 1.9);
	return elgg_load_system_cache($type);
}
/**
 * @access private
 * @deprecated 1.9 Use elgg_enable_system_cache()
 */
function elgg_enable_filepath_cache() {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated by elgg_enable_system_cache()', 1.9);
	elgg_enable_system_cache();
}
/**
 * @access private
 * @deprecated 1.9 Use elgg_disable_system_cache()
 */
function elgg_disable_filepath_cache() {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated by elgg_disable_system_cache()', 1.9);
	elgg_disable_system_cache();
}

/**
 * Unregisters an entity type and subtype as a public-facing type.
 *
 * @warning With a blank subtype, it unregisters that entity type including
 * all subtypes. This must be called after all subtypes have been registered.
 *
 * @param string $type    The type of entity (object, site, user, group)
 * @param string $subtype The subtype to register (may be blank)
 *
 * @return true|false Depending on success
 * @deprecated 1.9 Use {@link elgg_unregister_entity_type()}
 */
function unregister_entity_type($type, $subtype) {
	elgg_deprecated_notice("unregister_entity_type() was deprecated by elgg_unregister_entity_type()", 1.9);
	return elgg_unregister_entity_type($type, $subtype);
}

/**
 * Function to determine if the object trying to attach to other, has already done so
 *
 * @param int $guid_one This is the target object
 * @param int $guid_two This is the object trying to attach to $guid_one
 *
 * @return bool
 * @access private
 * @deprecated 1.9 Use check_entity_relationship()
 */
function already_attached($guid_one, $guid_two) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
	if ($attached = check_entity_relationship($guid_one, "attached", $guid_two)) {
		return true;
	} else {
		return false;
	}
}

/**
 * Function to get all objects attached to a particular object
 *
 * @param int    $guid Entity GUID
 * @param string $type The type of object to return e.g. 'file', 'friend_of' etc
 *
 * @return ElggObject[] array of objects
 * @access private
 * @deprecated 1.9 Use elgg_get_entities_from_relationship()
 */
function get_attachments($guid, $type = "") {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
	$options = array(
					'relationship' => 'attached',
					'relationship_guid' => $guid,
					'inverse_relationship' => false,
					'types' => $type,
					'subtypes' => '',
					'owner_guid' => 0,
					'order_by' => 'time_created desc',
					'limit' => 10,
					'offset' => 0,
					'count' => false,
					'site_guid' => 0
				);
	$attached = elgg_get_entities_from_relationship($options);
	return $attached;
}

/**
 * Function to remove a particular attachment between two objects
 *
 * @param int $guid_one This is the target object
 * @param int $guid_two This is the object to remove from $guid_one
 *
 * @return void
 * @access private
 * @deprecated 1.9 Use remove_entity_relationship()
 */
function remove_attachment($guid_one, $guid_two) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
	if (already_attached($guid_one, $guid_two)) {
		remove_entity_relationship($guid_one, "attached", $guid_two);
	}
}

/**
 * Function to start the process of attaching one object to another
 *
 * @param int $guid_one This is the target object
 * @param int $guid_two This is the object trying to attach to $guid_one
 *
 * @return true|void
 * @access private
 * @deprecated 1.9 Use add_entity_relationship()
 */
function make_attachment($guid_one, $guid_two) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
	if (!(already_attached($guid_one, $guid_two))) {
		if (add_entity_relationship($guid_one, "attached", $guid_two)) {
			return true;
		}
	}
}

/**
 * Returns the URL for an entity.
 *
 * @param int $entity_guid The GUID of the entity
 *
 * @return string The URL of the entity
 * @deprecated 1.9 Use \ElggEntity::getURL()
 */
function get_entity_url($entity_guid) {
	elgg_deprecated_notice('get_entity_url has been deprecated in favor of \ElggEntity::getURL', '1.9');
	if ($entity = get_entity($entity_guid)) {
		return $entity->getURL();
	}

	return false;
}

/**
 * Delete an entity.
 *
 * Removes an entity and its metadata, annotations, relationships, river entries,
 * and private data.
 *
 * Optionally can remove entities contained and owned by $guid.
 *
 * @warning If deleting recursively, this bypasses ownership of items contained by
 * the entity.  That means that if the container_guid = $guid, the item will be deleted
 * regardless of who owns it.
 *
 * @param int  $guid      The guid of the entity to delete
 * @param bool $recursive If true (default) then all entities which are
 *                        owned or contained by $guid will also be deleted.
 *
 * @return bool
 * @access private
 * @deprecated 1.9 Use \ElggEntity::delete() instead.
 */
function delete_entity($guid, $recursive = true) {
	elgg_deprecated_notice('delete_entity has been deprecated in favor of \ElggEntity::delete', '1.9');
	$guid = (int)$guid;
	if ($entity = get_entity($guid)) {
		return $entity->delete($recursive);
	}
	return false;
}

/**
 * Enable an entity.
 *
 * @warning In order to enable an entity using \ElggEntity::enable(),
 * you must first use {@link access_show_hidden_entities()}.
 *
 * @param int  $guid      GUID of entity to enable
 * @param bool $recursive Recursively enable all entities disabled with the entity?
 *
 * @return bool
 * @deprecated 1.9 Use elgg_enable_entity()
 */
function enable_entity($guid, $recursive = true) {
	elgg_deprecated_notice('enable_entity has been deprecated in favor of elgg_enable_entity', '1.9');
	
	$guid = (int)$guid;

	// Override access only visible entities
	$old_access_status = access_get_show_hidden_status();
	access_show_hidden_entities(true);

	$result = false;
	if ($entity = get_entity($guid)) {
		$result = $entity->enable($recursive);
	}

	access_show_hidden_entities($old_access_status);
	return $result;
}

/**
 * Returns if $user_guid can edit the metadata on $entity_guid.
 *
 * @tip Can be overridden by by registering for the permissions_check:metadata
 * plugin hook.
 *
 * @warning If a $user_guid isn't specified, the currently logged in user is used.
 *
 * @param int          $entity_guid The GUID of the entity
 * @param int          $user_guid   The GUID of the user
 * @param \ElggMetadata $metadata    The metadata to specifically check (if any; default null)
 *
 * @return bool Whether the user can edit metadata on the entity.
 * @deprecated 1.9 Use \ElggEntity::canEditMetadata
 */
function can_edit_entity_metadata($entity_guid, $user_guid = 0, $metadata = null) {
	elgg_deprecated_notice('can_edit_entity_metadata has been deprecated in favor of \ElggEntity::canEditMetadata', '1.9');
	if ($entity = get_entity($entity_guid)) {
		return $entity->canEditMetadata($metadata, $user_guid);
	} else {
		return false;
	}
}

/**
 * Disable an entity.
 *
 * Disabled entities do not show up in list or elgg_get_entities()
 * calls, but still exist in the database.
 *
 * Entities are disabled by setting disabled = yes in the
 * entities table.
 *
 * You can ignore the disabled field by using {@link access_show_hidden_entities()}.
 *
 * @param int    $guid      The guid
 * @param string $reason    Optional reason
 * @param bool   $recursive Recursively disable all entities owned or contained by $guid?
 *
 * @return bool
 * @see access_show_hidden_entities()
 * @access private
 * @deprecated 1.9 Use \ElggEntity::disable instead.
 */
function disable_entity($guid, $reason = "", $recursive = true) {
	elgg_deprecated_notice('disable_entity was deprecated in favor of \ElggEntity::disable', '1.9');
	
	if ($entity = get_entity($guid)) {
		return $entity->disable($reason, $recursive);
	}
	
	return false;
}

/**
 * Returns if $user_guid is able to edit $entity_guid.
 *
 * @tip Can be overridden by registering for the permissions_check plugin hook.
 *
 * @warning If a $user_guid is not passed it will default to the logged in user.
 *
 * @param int $entity_guid The GUID of the entity
 * @param int $user_guid   The GUID of the user
 *
 * @return bool
 * @deprecated 1.9 Use \ElggEntity::canEdit instead
 */
function can_edit_entity($entity_guid, $user_guid = 0) {
	elgg_deprecated_notice('can_edit_entity was deprecated in favor of \ElggEntity::canEdit', '1.9');
	if ($entity = get_entity($entity_guid)) {
		return $entity->canEdit($user_guid);
	}
	
	return false;
}

/**
 * Join a user to a group.
 *
 * @param int $group_guid The group GUID.
 * @param int $user_guid  The user GUID.
 *
 * @return bool
 * @deprecated 1.9 Use \ElggGroup::join instead.
 */
function join_group($group_guid, $user_guid) {
	elgg_deprecated_notice('join_group was deprecated in favor of \ElggGroup::join', '1.9');
	
	$group = get_entity($group_guid);
	$user = get_entity($user_guid);
	
	if ($group instanceof \ElggGroup && $user instanceof \ElggUser) {
		return $group->join($user);
	}
	
	return false;
}

/**
 * Remove a user from a group.
 *
 * @param int $group_guid The group.
 * @param int $user_guid  The user.
 *
 * @return bool Whether the user was removed from the group.
 * @deprecated 1.9 Use \ElggGroup::leave()
 */
function leave_group($group_guid, $user_guid) {
	elgg_deprecated_notice('leave_group was deprecated in favor of \ElggGroup::leave', '1.9');
	$group = get_entity($group_guid);
	$user = get_entity($user_guid);
	
	if ($group instanceof \ElggGroup && $user instanceof \ElggUser) {
		return $group->leave($user);
	}

	return false;
}

/**
 * Create paragraphs from text with line spacing
 *
 * @param string $string The string
 * @return string
 * @deprecated 1.9 Use elgg_autop instead
 **/
function autop($string) {
	elgg_deprecated_notice('autop has been deprecated in favor of elgg_autop', '1.9');
	return elgg_autop($string);
}

/**
 * Register a function as a web service method
 * 
 * @deprecated 1.9 Enable the web services plugin and use elgg_ws_expose_function().
 */
function expose_function($method, $function, array $parameters = NULL, $description = "",
		$call_method = "GET", $require_api_auth = false, $require_user_auth = false) {
	elgg_deprecated_notice("expose_function() deprecated for the function elgg_ws_expose_function() in web_services plugin", 1.9);
	if (!elgg_admin_notice_exists("elgg:ws:1.9")) {
		elgg_add_admin_notice("elgg:ws:1.9", "The web services are now a plugin in Elgg 1.9.
			You must enable this plugin and update your web services to use elgg_ws_expose_function().");
	}

	if (function_exists("elgg_ws_expose_function")) {
		return elgg_ws_expose_function($method, $function, $parameters, $description, $call_method, $require_api_auth, $require_user_auth);
	}
}

/**
 * Unregister a web services method
 *
 * @param string $method The api name that was exposed
 * @return void
 * @deprecated 1.9 Enable the web services plugin and use elgg_ws_unexpose_function().
 */
function unexpose_function($method) {
	elgg_deprecated_notice("unexpose_function() deprecated for the function elgg_ws_unexpose_function() in web_services plugin", 1.9);
	if (function_exists("elgg_ws_unexpose_function")) {
		return elgg_ws_unexpose_function($method);
	}
}

/**
 * Registers a web services handler
 *
 * @param string $handler  Web services type
 * @param string $function Your function name
 * @return bool Depending on success
 * @deprecated 1.9 Enable the web services plugin and use elgg_ws_register_service_handler().
 */
function register_service_handler($handler, $function) {
	elgg_deprecated_notice("register_service_handler() deprecated for the function elgg_ws_register_service_handler() in web_services plugin", 1.9);
	if (function_exists("elgg_ws_register_service_handler")) {
		return elgg_ws_register_service_handler($handler, $function);
	}
}

/**
 * Remove a web service
 * To replace a web service handler, register the desired handler over the old on
 * with register_service_handler().
 *
 * @param string $handler web services type
 * @return void
 * @deprecated 1.9 Enable the web services plugin and use elgg_ws_unregister_service_handler().
 */
function unregister_service_handler($handler) {
	elgg_deprecated_notice("unregister_service_handler() deprecated for the function elgg_ws_unregister_service_handler() in web_services plugin", 1.9);
	if (function_exists("elgg_ws_unregister_service_handler")) {
		return elgg_ws_unregister_service_handler($handler);
	}
}

/**
 * Create or update the entities table for a given site.
 * Call create_entity first.
 *
 * @param int    $guid        Site GUID
 * @param string $name        Site name
 * @param string $description Site Description
 * @param string $url         URL of the site
 *
 * @return bool
 * @access private
 * @deprecated 1.9 Use \ElggSite constructor
 */
function create_site_entity($guid, $name, $description, $url) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \ElggSite constructor', 1.9);
	global $CONFIG;

	$guid = (int)$guid;
	$name = sanitise_string($name);
	$description = sanitise_string($description);
	$url = sanitise_string($url);

	$row = get_entity_as_row($guid);

	if ($row) {
		// Exists and you have access to it
		$query = "SELECT guid from {$CONFIG->dbprefix}sites_entity where guid = {$guid}";
		if ($exists = get_data_row($query)) {
			$query = "UPDATE {$CONFIG->dbprefix}sites_entity
				set name='$name', description='$description', url='$url' where guid=$guid";
			$result = update_data($query);

			if ($result != false) {
				// Update succeeded, continue
				$entity = get_entity($guid);
				if (elgg_trigger_event('update', $entity->type, $entity)) {
					return $guid;
				} else {
					$entity->delete();
					//delete_entity($guid);
				}
			}
		} else {
			// Update failed, attempt an insert.
			$query = "INSERT into {$CONFIG->dbprefix}sites_entity
				(guid, name, description, url) values ($guid, '$name', '$description', '$url')";
			$result = insert_data($query);

			if ($result !== false) {
				$entity = get_entity($guid);
				if (elgg_trigger_event('create', $entity->type, $entity)) {
					return $guid;
				} else {
					$entity->delete();
					//delete_entity($guid);
				}
			}
		}
	}

	return false;
}

/**
 * Create or update the entities table for a given group.
 * Call create_entity first.
 *
 * @param int    $guid        GUID
 * @param string $name        Name
 * @param string $description Description
 *
 * @return bool
 * @access private
 * @deprecated 1.9 Use \ElggGroup constructor
 */
function create_group_entity($guid, $name, $description) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \ElggGroup constructor', 1.9);
	global $CONFIG;

	$guid = (int)$guid;
	$name = sanitise_string($name);
	$description = sanitise_string($description);

	$row = get_entity_as_row($guid);

	if ($row) {
		// Exists and you have access to it
		$exists = get_data_row("SELECT guid from {$CONFIG->dbprefix}groups_entity WHERE guid = {$guid}");
		if ($exists) {
			$query = "UPDATE {$CONFIG->dbprefix}groups_entity set"
				. " name='$name', description='$description' where guid=$guid";
			$result = update_data($query);
			if ($result != false) {
				// Update succeeded, continue
				$entity = get_entity($guid);
				if (elgg_trigger_event('update', $entity->type, $entity)) {
					return $guid;
				} else {
					$entity->delete();
				}
			}
		} else {
			// Update failed, attempt an insert.
			$query = "INSERT into {$CONFIG->dbprefix}groups_entity"
				. " (guid, name, description) values ($guid, '$name', '$description')";

			$result = insert_data($query);
			if ($result !== false) {
				$entity = get_entity($guid);
				if (elgg_trigger_event('create', $entity->type, $entity)) {
					return $guid;
				} else {
					$entity->delete();
				}
			}
		}
	}

	return false;
}

/**
 * Create or update the entities table for a given user.
 * Call create_entity first.
 *
 * @param int    $guid     The user's GUID
 * @param string $name     The user's display name
 * @param string $username The username
 * @param string $password The password
 * @param string $salt     A salt for the password
 * @param string $email    The user's email address
 * @param string $language The user's default language
 * @param string $code     A code
 *
 * @return bool
 * @access private
 * @deprecated 1.9 Use \ElggUser constructor
 */
function create_user_entity($guid, $name, $username, $password, $salt, $email, $language, $code) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \ElggUser constructor', 1.9);
	global $CONFIG;

	$guid = (int)$guid;
	$name = sanitise_string($name);
	$username = sanitise_string($username);
	$password = sanitise_string($password);
	$salt = sanitise_string($salt);
	$email = sanitise_string($email);
	$language = sanitise_string($language);

	$row = get_entity_as_row($guid);
	if ($row) {
		// Exists and you have access to it
		$query = "SELECT guid from {$CONFIG->dbprefix}users_entity where guid = {$guid}";
		if ($exists = get_data_row($query)) {
			$query = "UPDATE {$CONFIG->dbprefix}users_entity
				SET name='$name', username='$username', password='$password', salt='$salt',
				email='$email', language='$language'
				WHERE guid = $guid";

			$result = update_data($query);
			if ($result != false) {
				// Update succeeded, continue
				$entity = get_entity($guid);
				if (elgg_trigger_event('update', $entity->type, $entity)) {
					return $guid;
				} else {
					$entity->delete();
				}
			}
		} else {
			// Exists query failed, attempt an insert.
			$query = "INSERT into {$CONFIG->dbprefix}users_entity
				(guid, name, username, password, salt, email, language)
				values ($guid, '$name', '$username', '$password', '$salt', '$email', '$language')";

			$result = insert_data($query);
			if ($result !== false) {
				$entity = get_entity($guid);
				if (elgg_trigger_event('create', $entity->type, $entity)) {
					return $guid;
				} else {
					$entity->delete();
				}
			}
		}
	}

	return false;
}

/**
 * Create or update the extras table for a given object.
 * Call create_entity first.
 *
 * @param int    $guid        The guid of the entity you're creating (as obtained by create_entity)
 * @param string $title       The title of the object
 * @param string $description The object's description
 *
 * @return bool
 * @access private
 * @deprecated 1.9 Use \ElggObject constructor
 */
function create_object_entity($guid, $title, $description) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \ElggObject constructor', 1.9);
	global $CONFIG;

	$guid = (int)$guid;
	$title = sanitise_string($title);
	$description = sanitise_string($description);

	$row = get_entity_as_row($guid);

	if ($row) {
		// Core entities row exists and we have access to it
		$query = "SELECT guid from {$CONFIG->dbprefix}objects_entity where guid = {$guid}";
		if ($exists = get_data_row($query)) {
			$query = "UPDATE {$CONFIG->dbprefix}objects_entity
				set title='$title', description='$description' where guid=$guid";

			$result = update_data($query);
			if ($result != false) {
				// Update succeeded, continue
				$entity = get_entity($guid);
				elgg_trigger_event('update', $entity->type, $entity);
				return $guid;
			}
		} else {
			// Update failed, attempt an insert.
			$query = "INSERT into {$CONFIG->dbprefix}objects_entity
				(guid, title, description) values ($guid, '$title','$description')";

			$result = insert_data($query);
			if ($result !== false) {
				$entity = get_entity($guid);
				if (elgg_trigger_event('create', $entity->type, $entity)) {
					return $guid;
				} else {
					$entity->delete();
				}
			}
		}
	}

	return false;
}

/**
 * Attempt to construct an ODD object out of a XmlElement or sub-elements.
 *
 * @param XmlElement $element The element(s)
 *
 * @return mixed An ODD object if the element can be handled, or false.
 * @access private
 * @deprecated 1.9
 */
function ODD_factory (XmlElement $element) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
	$name = $element->name;
	$odd = false;

	switch ($name) {
		case 'entity' :
			$odd = new ODDEntity("", "", "");
			break;
		case 'metadata' :
			$odd = new ODDMetaData("", "", "", "");
			break;
		case 'relationship' :
			$odd = new ODDRelationship("", "", "");
			break;
	}

	// Now populate values
	if ($odd) {
		// Attributes
		foreach ($element->attributes as $k => $v) {
			$odd->setAttribute($k, $v);
		}

		// Body
		$body = $element->content;
		$a = stripos($body, "<![CDATA");
		$b = strripos($body, "]]>");
		if (($body) && ($a !== false) && ($b !== false)) {
			$body = substr($body, $a + 8, $b - ($a + 8));
		}

		$odd->setBody($body);
	}

	return $odd;
}

/**
 * Utility function used by import_entity_plugin_hook() to
 * process an ODDEntity into an unsaved \ElggEntity.
 *
 * @param ODDEntity $element The OpenDD element
 *
 * @return \ElggEntity the unsaved entity which should be populated by items.
 * @todo Remove this.
 * @access private
 *
 * @throws ClassException|InstallationException|ImportException
 * @deprecated 1.9
 */
function oddentity_to_elggentity(ODDEntity $element) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
	$class = $element->getAttribute('class');
	$subclass = $element->getAttribute('subclass');

	// See if we already have imported this uuid
	$tmp = get_entity_from_uuid($element->getAttribute('uuid'));

	if (!$tmp) {
		// Construct new class with owner from session
		$classname = get_subtype_class($class, $subclass);
		if ($classname) {
			if (class_exists($classname)) {
				$tmp = new $classname();

				if (!($tmp instanceof \ElggEntity)) {
					$msg = $classname . " is not a " . get_class() . ".";
					throw new \ClassException($msg);
				}
			} else {
				error_log("Class '" . $classname . "' was not found, missing plugin?");
			}
		} else {
			switch ($class) {
				case 'object' :
					$tmp = new \ElggObject();
					break;
				case 'user' :
					$tmp = new \ElggUser();
					break;
				case 'group' :
					$tmp = new \ElggGroup();
					break;
				case 'site' :
					$tmp = new \ElggSite();
					break;
				default:
					$msg = "Type " . $class . " is not supported. This indicates an error in your installation, most likely caused by an incomplete upgrade.";
					throw new \InstallationException($msg);
			}
		}
	}

	if ($tmp) {
		if (!$tmp->import($element)) {
			$msg = "Could not import element " . $element->getAttribute('uuid');
			throw new \ImportException($msg);
		}

		return $tmp;
	}

	return NULL;
}

/**
 * Import an ODD document.
 *
 * @param string $xml The XML ODD.
 *
 * @return ODDDocument
 * @access private
 * @deprecated 1.9
 */
function ODD_Import($xml) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
	// Parse XML to an array
	$elements = xml_to_object($xml);

	// Sanity check 1, was this actually XML?
	if ((!$elements) || (!$elements->children)) {
		return false;
	}

	// Create ODDDocument
	$document = new ODDDocument();

	// Itterate through array of elements and construct ODD document
	$cnt = 0;

	foreach ($elements->children as $child) {
		$odd = ODD_factory($child);

		if ($odd) {
			$document->addElement($odd);
			$cnt++;
		}
	}

	// Check that we actually found something
	if ($cnt == 0) {
		return false;
	}

	return $document;
}

/**
 * Export an ODD Document.
 *
 * @param ODDDocument $document The Document.
 *
 * @return string
 * @access private
 * @deprecated 1.9
 */
function ODD_Export(ODDDocument $document) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
	return "$document";
}

/**
 * Get a UUID from a given object.
 *
 * @param mixed $object The object either an \ElggEntity, \ElggRelationship or \ElggExtender
 *
 * @return string|false the UUID or false
 * @deprecated 1.9
 */
function get_uuid_from_object($object) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
	if ($object instanceof \ElggEntity) {
		return guid_to_uuid($object->guid);
	} else if ($object instanceof \ElggExtender) {
		$type = $object->type;
		if ($type == 'volatile') {
			$uuid = guid_to_uuid($object->entity_guid) . $type . "/{$object->name}/";
		} else {
			$uuid = guid_to_uuid($object->entity_guid) . $type . "/{$object->id}/";
		}

		return $uuid;
	} else if ($object instanceof \ElggRelationship) {
		return guid_to_uuid($object->guid_one) . "relationship/{$object->id}/";
	}

	return false;
}

/**
 * Generate a UUID from a given GUID.
 *
 * @param int $guid The GUID of an object.
 *
 * @return string
 * @deprecated 1.9
 */
function guid_to_uuid($guid) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
	return elgg_get_site_url()  . "export/opendd/$guid/";
}

/**
 * Test to see if a given uuid is for this domain, returning true if so.
 *
 * @param string $uuid A unique ID
 *
 * @return bool
 * @deprecated 1.9
 */
function is_uuid_this_domain($uuid) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
	if (strpos($uuid, elgg_get_site_url()) === 0) {
		return true;
	}

	return false;
}

/**
 * This function attempts to retrieve a previously imported entity via its UUID.
 *
 * @param string $uuid A unique ID
 *
 * @return \ElggEntity|false
 * @deprecated 1.9
 */
function get_entity_from_uuid($uuid) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
	$uuid = sanitise_string($uuid);

	$options = array('metadata_name' => 'import_uuid', 'metadata_value' => $uuid);
	$entities = elgg_get_entities_from_metadata($options);

	if ($entities) {
		return $entities[0];
	}

	return false;
}

/**
 * Tag a previously created guid with the uuid it was imported on.
 *
 * @param int    $guid A GUID
 * @param string $uuid A Unique ID
 *
 * @return bool
 * @deprecated 1.9
 */
function add_uuid_to_guid($guid, $uuid) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
	$guid = (int)$guid;
	$uuid = sanitise_string($uuid);

	$result = create_metadata($guid, "import_uuid", $uuid);
	return (bool)$result;
}


$IMPORTED_DATA = array();
$IMPORTED_OBJECT_COUNTER = 0;

/**
 * This function processes an element, passing elements to the plugin stack to see if someone will
 * process it.
 *
 * If nobody processes the top level element, the sub level elements are processed.
 *
 * @param ODD $odd The odd element to process
 *
 * @return bool
 * @access private
 * @deprecated 1.9
 */
function _process_element(ODD $odd) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
	global $IMPORTED_DATA, $IMPORTED_OBJECT_COUNTER;

	// See if anyone handles this element, return true if it is.
	$to_be_serialised = null;
	if ($odd) {
		$handled = elgg_trigger_plugin_hook("import", "all", array("element" => $odd), $to_be_serialised);

		// If not, then see if any of its sub elements are handled
		if ($handled) {
			// Increment validation counter
			$IMPORTED_OBJECT_COUNTER ++;
			// Return the constructed object
			$IMPORTED_DATA[] = $handled;

			return true;
		}
	}

	return false;
}

/**
 * Exports an entity as an array
 *
 * @param int $guid Entity GUID
 *
 * @return array
 * @throws ExportException
 * @access private
 * @deprecated 1.9
 */
function exportAsArray($guid) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
	$guid = (int)$guid;

	// Trigger a hook to
	$to_be_serialised = elgg_trigger_plugin_hook("export", "all", array("guid" => $guid), array());

	// Sanity check
	if ((!is_array($to_be_serialised)) || (count($to_be_serialised) == 0)) {
		throw new \ExportException("No such entity GUID:" . $guid);
	}

	return $to_be_serialised;
}

/**
 * Export a GUID.
 *
 * This function exports a GUID and all information related to it in an XML format.
 *
 * This function makes use of the "serialise" plugin hook, which is passed an array to which plugins
 * should add data to be serialised to.
 *
 * @param int $guid The GUID.
 *
 * @return string XML
 * @see \ElggEntity for an example of its usage.
 * @access private
 * @deprecated 1.9
 */
function export($guid) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
	$odd = new ODDDocument(exportAsArray($guid));

	return ODD_Export($odd);
}

/**
 * Import an XML serialisation of an object.
 * This will make a best attempt at importing a given xml doc.
 *
 * @param string $xml XML string
 *
 * @return bool
 * @throws ImportException if there was a problem importing the data.
 * @access private
 * @deprecated 1.9
 */
function import($xml) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated', 1.9);
	global $IMPORTED_DATA, $IMPORTED_OBJECT_COUNTER;

	$IMPORTED_DATA = array();
	$IMPORTED_OBJECT_COUNTER = 0;

	$document = ODD_Import($xml);
	if (!$document) {
		throw new \ImportException("No OpenDD elements found in import data, import failed.");
	}

	foreach ($document as $element) {
		_process_element($element);
	}

	if ($IMPORTED_OBJECT_COUNTER != count($IMPORTED_DATA)) {
		throw new \ImportException("Not all elements were imported.");
	}

	return true;
}

/**
 * Register the OpenDD import action
 *
 * @return void
 * @access private
 * @deprecated 1.9
 */
function _export_init() {
	global $CONFIG;

	elgg_register_action("import/opendd");
}

/**
 * Returns the name of views for in a directory.
 *
 * Use this to get all namespaced views under the first element.
 *
 * @param string $dir  The main directory that holds the views. (mod/profile/views/)
 * @param string $base The root name of the view to use, without the viewtype. (profile)
 *
 * @return array
 * @since 1.7.0
 * @todo Why isn't this used anywhere else but in elgg_view_tree()?
 * Seems like a useful function for autodiscovery.
 * @access private
 * @deprecated 1.9
 */
function elgg_get_views($dir, $base) {
	$return = array();
	if (file_exists($dir) && is_dir($dir)) {
		if ($handle = opendir($dir)) {
			while ($view = readdir($handle)) {
				if (!in_array($view, array('.', '..', '.svn', 'CVS'))) {
					if (is_dir($dir . '/' . $view)) {
						if ($val = elgg_get_views($dir . '/' . $view, $base . '/' . $view)) {
							$return = array_merge($return, $val);
						}
					} else {
						$view = str_replace('.php', '', $view);
						$return[] = $base . '/' . $view;
					}
				}
			}
		}
	}

	return $return;
}

/**
 * Returns all views below a partial view.
 *
 * Settings $view_root = 'profile' will show all available views under
 * the "profile" namespace.
 *
 * @param string $view_root The root view
 * @param string $viewtype  Optionally specify a view type
 *                          other than the current one.
 *
 * @return array A list of view names underneath that root view
 * @todo This is used once in the deprecated get_activity_stream_data() function.
 * @access private
 * @deprecated 1.9
 */
function elgg_view_tree($view_root, $viewtype = "") {
	global $CONFIG;
	static $treecache = array();

	// Get viewtype
	if (!$viewtype) {
		$viewtype = elgg_get_viewtype();
	}

	// A little light internal caching
	if (!empty($treecache[$view_root])) {
		return $treecache[$view_root];
	}

	// Examine $CONFIG->views->locations
	if (isset($CONFIG->views->locations[$viewtype])) {
		foreach ($CONFIG->views->locations[$viewtype] as $view => $path) {
			$pos = strpos($view, $view_root);
			if ($pos === 0) {
				$treecache[$view_root][] = $view;
			}
		}
	}

	// Now examine core
	$location = $CONFIG->viewpath;
	$viewtype = elgg_get_viewtype();
	$root = $location . $viewtype . '/' . $view_root;

	if (file_exists($root) && is_dir($root)) {
		$val = elgg_get_views($root, $view_root);
		if (!is_array($treecache[$view_root])) {
			$treecache[$view_root] = array();
		}
		$treecache[$view_root] = array_merge($treecache[$view_root], $val);
	}

	return $treecache[$view_root];
}

/**
 * Adds an item to the river.
 *
 * @param string $view          The view that will handle the river item (must exist)
 * @param string $action_type   An arbitrary string to define the action (eg 'comment', 'create')
 * @param int    $subject_guid  The GUID of the entity doing the action
 * @param int    $object_guid   The GUID of the entity being acted upon
 * @param int    $access_id     The access ID of the river item (default: same as the object)
 * @param int    $posted        The UNIX epoch timestamp of the river item (default: now)
 * @param int    $annotation_id The annotation ID associated with this river entry
 * @param int    $target_guid   The GUID of the the object entity's container
 *
 * @return int/bool River ID or false on failure
 * @deprecated 1.9 Use elgg_create_river_item()
 */
function add_to_river($view, $action_type, $subject_guid, $object_guid, $access_id = "",
$posted = 0, $annotation_id = 0, $target_guid = 0) {
	elgg_deprecated_notice('add_to_river was deprecated in favor of elgg_create_river_item', '1.9');

	// Make sure old parameters are passed in correct format
	$access_id = ($access_id == '') ? null : $access_id;
	$posted = ($posted == 0) ? null : $posted;

	return elgg_create_river_item(array(
		'view' => $view,
		'action_type' => $action_type,
		'subject_guid' => $subject_guid,
		'object_guid' => $object_guid,
		'target_guid' => $target_guid,
		'access_id' => $access_id,
		'posted' => $posted,
		'annotation_id' => $annotation_id,
	));
}

/**
 * This function serialises an object recursively into an XML representation.
 *
 * The function attempts to call $data->export() which expects a \stdClass in return,
 * otherwise it will attempt to get the object variables using get_object_vars (which
 * will only return public variables!)
 *
 * @param mixed  $data The object to serialise.
 * @param string $name The name?
 * @param int    $n    Level, only used for recursion.
 *
 * @return string The serialised XML output.
 * @deprecated 1.9
 */
function serialise_object_to_xml($data, $name = "", $n = 0) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated.', 1.9);

	$classname = ($name == "" ? get_class($data) : $name);

	$vars = method_exists($data, "export") ? get_object_vars($data->export()) : get_object_vars($data);

	$output = "";

	if (($n == 0) || ( is_object($data) && !($data instanceof \stdClass))) {
		$output = "<$classname>";
	}

	foreach ($vars as $key => $value) {
		$output .= "<$key type=\"" . gettype($value) . "\">";

		if (is_object($value)) {
			$output .= serialise_object_to_xml($value, $key, $n + 1);
		} else if (is_array($value)) {
			$output .= serialise_array_to_xml($value, $n + 1);
		} else if (gettype($value) == "boolean") {
			$output .= $value ? "true" : "false";
		} else {
			$output .= htmlspecialchars($value, ENT_NOQUOTES, 'UTF-8');
		}

		$output .= "</$key>\n";
	}

	if (($n == 0) || (is_object($data) && !($data instanceof \stdClass))) {
		$output .= "</$classname>\n";
	}

	return $output;
}

/**
 * Serialise an array.
 *
 * @param array $data The data to serialize
 * @param int   $n    Used for recursion
 *
 * @return string
 * @deprecated 1.9
 */
function serialise_array_to_xml(array $data, $n = 0) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated.', 1.9);

	$output = "";

	if ($n == 0) {
		$output = "<array>\n";
	}

	foreach ($data as $key => $value) {
		$item = "array_item";

		if (is_numeric($key)) {
			$output .= "<$item name=\"$key\" type=\"" . gettype($value) . "\">";
		} else {
			$item = $key;
			$output .= "<$item type=\"" . gettype($value) . "\">";
		}

		if (is_object($value)) {
			$output .= serialise_object_to_xml($value, "", $n + 1);
		} else if (is_array($value)) {
			$output .= serialise_array_to_xml($value, $n + 1);
		} else if (gettype($value) == "boolean") {
			$output .= $value ? "true" : "false";
		} else {
			$output .= htmlspecialchars($value, ENT_NOQUOTES, 'UTF-8');
		}

		$output .= "</$item>\n";
	}

	if ($n == 0) {
		$output .= "</array>\n";
	}

	return $output;
}

/**
 * Parse an XML file into an object.
 *
 * @param string $xml The XML
 *
 * @return \ElggXMLElement
 * @deprecated 1.9 Use \ElggXMLElement
 */
function xml_to_object($xml) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated by \ElggXMLElement', 1.9);
	return new \ElggXMLElement($xml);
}

/**
 * Retrieve a site and return the domain portion of its url.
 *
 * @param int $guid \ElggSite GUID
 *
 * @return string
 * @deprecated 1.9 Use \ElggSite::getDomain()
 */
function get_site_domain($guid) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \ElggSite::getDomain()', 1.9);
	$guid = (int)$guid;

	$site = get_entity($guid);
	if ($site instanceof \ElggSite) {
		$breakdown = parse_url($site->url);
		return $breakdown['host'];
	}

	return false;
}

/**
 * Register an entity type and subtype to be eligible for notifications
 *
 * @param string $entity_type    The type of entity
 * @param string $object_subtype Its subtype
 * @param string $language_name  Its localized notification string (eg "New blog post")
 *
 * @return void
 * @deprecated 1.9 Use elgg_register_notification_event(). The 3rd argument was used 
 * as the subject line in a notification. As of Elgg 1.9, it is now set by a callback
 * for a plugin hook. See the documentation at the top of the notifications library
 * titled "Adding a New Notification Event" for more details.
 */
function register_notification_object($entity_type, $object_subtype, $language_name) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated by elgg_register_notification_event()', 1.9);

	elgg_register_notification_event($entity_type, $object_subtype);
	_elgg_services()->notifications->setDeprecatedNotificationSubject($entity_type, $object_subtype, $language_name);
}

/**
 * Establish a 'notify' relationship between the user and a content author
 *
 * @param int $user_guid   The GUID of the user who wants to follow a user's content
 * @param int $author_guid The GUID of the user whose content the user wants to follow
 *
 * @return bool Depending on success
 * @deprecated 1.9 Use elgg_add_subscription()
 */
function register_notification_interest($user_guid, $author_guid) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated by elgg_add_subscription()', 1.9);
	return add_entity_relationship($user_guid, 'notify', $author_guid);
}

/**
 * Remove a 'notify' relationship between the user and a content author
 *
 * @param int $user_guid   The GUID of the user who is following a user's content
 * @param int $author_guid The GUID of the user whose content the user wants to unfollow
 *
 * @return bool Depending on success
 * @deprecated 1.9 Use elgg_remove_subscription()
 */
function remove_notification_interest($user_guid, $author_guid) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated by elgg_remove_subscription()', 1.9);
	return remove_entity_relationship($user_guid, 'notify', $author_guid);
}

/**
 * Automatically triggered notification on 'create' events that looks at registered
 * objects and attempts to send notifications to anybody who's interested
 *
 * @see register_notification_object
 *
 * @param string $event       create
 * @param string $object_type mixed
 * @param mixed  $object      The object created
 *
 * @return bool
 * @access private
 * @deprecated 1.9
 */
function object_notifications($event, $object_type, $object) {
	throw new \BadFunctionCallException("object_notifications is a private function and should not be called directly");
}

/**
 * This function registers a handler for a given notification type (eg "email")
 *
 * @param string $method  The method
 * @param string $handler The handler function, in the format
 *                        "handler(\ElggEntity $from, \ElggUser $to, $subject,
 *                        $message, array $params = NULL)". This function should
 *                        return false on failure, and true/a tracking message ID on success.
 * @param array  $params  An associated array of other parameters for this handler
 *                        defining some properties eg. supported msg length or rich text support.
 *
 * @return bool
 * @deprecated 1.9 Use elgg_register_notification_method()
 */
function register_notification_handler($method, $handler, $params = NULL) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated by elgg_register_notification_method()', 1.9);
	elgg_register_notification_method($method);
	_elgg_services()->notifications->registerDeprecatedHandler($method, $handler);
}

/**
 * This function unregisters a handler for a given notification type (eg "email")
 *
 * @param string $method The method
 *
 * @return void
 * @since 1.7.1
 * @deprecated 1.9 Use elgg_unregister_notification_method()
 */
function unregister_notification_handler($method) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated by elgg_unregister_notification_method()', 1.9);
	elgg_unregister_notification_method($method);
}

/**
 * Import an entity.
 *
 * This function checks the passed XML doc (as array) to see if it is
 * a user, if so it constructs a new elgg user and returns "true"
 * to inform the importer that it's been handled.
 *
 * @param string $hook        import
 * @param string $entity_type all
 * @param mixed  $returnvalue Value from previous hook
 * @param mixed  $params      Array of params
 *
 * @return mixed
 * @elgg_plugin_hook_handler import all
 * @todo document
 * @access private
 *
 * @throws ImportException
 * @deprecated 1.9
 */
function import_entity_plugin_hook($hook, $entity_type, $returnvalue, $params) {
	$element = $params['element'];

	$tmp = null;

	if ($element instanceof ODDEntity) {
		$tmp = oddentity_to_elggentity($element);

		if ($tmp) {
			// Make sure its saved
			if (!$tmp->save()) {
				$msg = "There was a problem saving " . $element->getAttribute('uuid');
				throw new \ImportException($msg);
			}

			// Belts and braces
			if (!$tmp->guid) {
				throw new \ImportException("New entity created but has no GUID, this should not happen.");
			}

			// We have saved, so now tag
			add_uuid_to_guid($tmp->guid, $element->getAttribute('uuid'));

			return $tmp;
		}
	}
}

/**
 * Exports all attributes of an entity.
 *
 * @warning Only exports fields in the entity and entity type tables.
 *
 * @param string $hook        export
 * @param string $entity_type all
 * @param mixed  $returnvalue Previous hook return value
 * @param array  $params      Parameters
 *
 * @elgg_event_handler export all
 * @return mixed
 * @access private
 *
 * @throws InvalidParameterException|InvalidClassException
 * @deprecated 1.9
 */
function export_entity_plugin_hook($hook, $entity_type, $returnvalue, $params) {
	// Sanity check values
	if ((!is_array($params)) && (!isset($params['guid']))) {
		throw new \InvalidParameterException("GUID has not been specified during export, this should never happen.");
	}

	if (!is_array($returnvalue)) {
		throw new \InvalidParameterException("Entity serialisation function passed a non-array returnvalue parameter");
	}

	$guid = (int)$params['guid'];

	// Get the entity
	$entity = get_entity($guid);
	if (!($entity instanceof \ElggEntity)) {
		$msg = "GUID:" . $guid . " is not a valid " . get_class();
		throw new \InvalidClassException($msg);
	}

	$export = $entity->export();

	if (is_array($export)) {
		foreach ($export as $e) {
			$returnvalue[] = $e;
		}
	} else {
		$returnvalue[] = $export;
	}

	return $returnvalue;
}

/**
 * Exports attributes generated on the fly (volatile) about an entity.
 *
 * @param string $hook        volatile
 * @param string $entity_type metadata
 * @param string $returnvalue Return value from previous hook
 * @param array  $params      The parameters, passed 'guid' and 'varname'
 *
 * @return \ElggMetadata|null
 * @elgg_plugin_hook_handler volatile metadata
 * @todo investigate more.
 * @access private
 * @todo document
 * @deprecated 1.9
 */
function volatile_data_export_plugin_hook($hook, $entity_type, $returnvalue, $params) {
	$guid = (int)$params['guid'];
	$variable_name = sanitise_string($params['varname']);

	if (($hook == 'volatile') && ($entity_type == 'metadata')) {
		if (($guid) && ($variable_name)) {
			switch ($variable_name) {
				case 'renderedentity' :
					elgg_set_viewtype('default');
					$view = elgg_view_entity(get_entity($guid));
					elgg_set_viewtype();

					$tmp = new \ElggMetadata();
					$tmp->type = 'volatile';
					$tmp->name = 'renderedentity';
					$tmp->value = $view;
					$tmp->entity_guid = $guid;

					return $tmp;

				break;
			}
		}
	}
}

/**
 * Export the annotations for the specified entity
 *
 * @param string $hook        'export'
 * @param string $type        'all'
 * @param mixed  $returnvalue Default return value
 * @param mixed  $params      Parameters determining what annotations to export
 *
 * @elgg_plugin_hook export all
 *
 * @return array
 * @throws InvalidParameterException
 * @access private
 * @deprecated 1.9
 */
function export_annotation_plugin_hook($hook, $type, $returnvalue, $params) {
	// Sanity check values
	if ((!is_array($params)) && (!isset($params['guid']))) {
		throw new \InvalidParameterException("GUID has not been specified during export, this should never happen.");
	}

	if (!is_array($returnvalue)) {
		throw new \InvalidParameterException("Entity serialization function passed a non-array returnvalue parameter");
	}

	$guid = (int)$params['guid'];
	$options = array('guid' => $guid, 'limit' => 0);
	if (isset($params['name'])) {
		$options['annotation_name'] = $params['name'];
	}

	$result = elgg_get_annotations($options);

	if ($result) {
		foreach ($result as $r) {
			$returnvalue[] = $r->export();
		}
	}

	return $returnvalue;
}

/**
 *  Handler called by trigger_plugin_hook on the "import" event.
 *
 * @param string $hook        volatile
 * @param string $entity_type metadata
 * @param string $returnvalue Return value from previous hook
 * @param array  $params      The parameters
 *
 * @return null
 * @elgg_plugin_hook_handler volatile metadata
 * @todo investigate more.
 * @throws ImportException
 * @access private
 * @deprecated 1.9
 */
function import_extender_plugin_hook($hook, $entity_type, $returnvalue, $params) {
	$element = $params['element'];

	$tmp = NULL;

	if ($element instanceof ODDMetaData) {
		/* @var ODDMetaData $element */
		// Recall entity
		$entity_uuid = $element->getAttribute('entity_uuid');
		$entity = get_entity_from_uuid($entity_uuid);
		if (!$entity) {
			throw new \ImportException("Entity '" . $entity_uuid . "' could not be found.");
		}

		oddmetadata_to_elggextender($entity, $element);

		// Save
		if (!$entity->save()) {
			$attr_name = $element->getAttribute('name');
			$msg = "There was a problem updating '" . $attr_name . "' on entity '" . $entity_uuid . "'";
			throw new \ImportException($msg);
		}

		return true;
	}
}

/**
 * Utility function used by import_extender_plugin_hook() to process
 * an ODDMetaData and add it to an entity. This function does not
 * hit ->save() on the entity (this lets you construct in memory)
 *
 * @param \ElggEntity  $entity  The entity to add the data to.
 * @param ODDMetaData $element The OpenDD element
 *
 * @return bool
 * @access private
 * @deprecated 1.9
 */
function oddmetadata_to_elggextender(\ElggEntity $entity, ODDMetaData $element) {
	// Get the type of extender (metadata, type, attribute etc)
	$type = $element->getAttribute('type');
	$attr_name = $element->getAttribute('name');
	$attr_val = $element->getBody();

	switch ($type) {
		// Ignore volatile items
		case 'volatile' :
			break;
		case 'annotation' :
			$entity->annotate($attr_name, $attr_val);
			break;
		case 'metadata' :
			$entity->setMetadata($attr_name, $attr_val, "", true);
			break;
		default : // Anything else assume attribute
			$entity->set($attr_name, $attr_val);
	}

	// Set time if appropriate
	$attr_time = $element->getAttribute('published');
	if ($attr_time) {
		$entity->set('time_updated', $attr_time);
	}

	return true;
}

/**
 * Handler called by trigger_plugin_hook on the "export" event.
 *
 * @param string $hook        export
 * @param string $entity_type all
 * @param mixed  $returnvalue Value returned from previous hook
 * @param mixed  $params      Params
 *
 * @return array
 * @access private
 *
 * @throws InvalidParameterException
 * @deprecated 1.9
 */
function export_metadata_plugin_hook($hook, $entity_type, $returnvalue, $params) {
	// Sanity check values
	if ((!is_array($params)) && (!isset($params['guid']))) {
		throw new \InvalidParameterException("GUID has not been specified during export, this should never happen.");
	}

	if (!is_array($returnvalue)) {
		throw new \InvalidParameterException("Entity serialisation function passed a non-array returnvalue parameter");
	}

	$result = elgg_get_metadata(array(
		'guid' => (int)$params['guid'],
		'limit' => 0,
	));

	if ($result) {
		/* @var \ElggMetadata[] $result */
		foreach ($result as $r) {
			$returnvalue[] = $r->export();
		}
	}

	return $returnvalue;
}

/**
 * Handler called by trigger_plugin_hook on the "export" event.
 *
 * @param string $hook        export
 * @param string $entity_type all
 * @param mixed  $returnvalue Previous hook return value
 * @param array  $params      Parameters
 *
 * @elgg_event_handler export all
 * @return mixed
 * @throws InvalidParameterException
 * @access private
 * @deprecated 1.9
 */
function export_relationship_plugin_hook($hook, $entity_type, $returnvalue, $params) {
	// Sanity check values
	if ((!is_array($params)) && (!isset($params['guid']))) {
		throw new \InvalidParameterException("GUID has not been specified during export, this should never happen.");
	}

	if (!is_array($returnvalue)) {
		throw new \InvalidParameterException("Entity serialisation function passed a non-array returnvalue parameter");
	}

	$guid = (int)$params['guid'];

	$result = get_entity_relationships($guid);

	if ($result) {
		foreach ($result as $r) {
			$returnvalue[] = $r->export();
		}
	}

	return $returnvalue;
}

/**
 * Handler called by trigger_plugin_hook on the "import" event.
 *
 * @param string $hook        import
 * @param string $entity_type all
 * @param mixed  $returnvalue Value from previous hook
 * @param mixed  $params      Array of params
 *
 * @return mixed
 * @access private
 * @deprecated 1.9
 */
function import_relationship_plugin_hook($hook, $entity_type, $returnvalue, $params) {
	$element = $params['element'];

	$tmp = NULL;

	if ($element instanceof ODDRelationship) {
		$tmp = new \ElggRelationship();
		$tmp->import($element);

		return $tmp;
	}
	return $tmp;
}

/**
 * Returns the SQL where clause for a table with access_id and enabled columns.
 *
 * This handles returning where clauses for ACCESS_FRIENDS in addition to using 
 * get_access_list() for access collections and the standard access levels.
 *
 * Note that if this code is executed in privileged mode it will return (1=1).
 *
 * @param string $table_prefix Optional table prefix for the access code.
 * @param int    $owner        Optional user guid to get access information for. Defaults
 *                             to logged in user.
 * @return string
 * @access private
 * @deprecated 1.9 Use _elgg_get_access_where_sql()
 */
function get_access_sql_suffix($table_prefix = '', $owner = null) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated by _elgg_get_access_where_sql()', 1.9);
	return _elgg_get_access_where_sql(array(
		'table_alias' => $table_prefix,
		'user_guid' => (int)$owner,
	));
}

/**
 * Get the name of the most recent plugin to be called in the
 * call stack (or the plugin that owns the current page, if any).
 *
 * i.e., if the last plugin was in /mod/foobar/, this would return foo_bar.
 *
 * @param boolean $mainfilename If set to true, this will instead determine the
 *                              context from the main script filename called by
 *                              the browser. Default = false.
 *
 * @return string|false Plugin name, or false if no plugin name was called
 * @since 1.8.0
 * @access private
 * @deprecated 1.9
 */
function elgg_get_calling_plugin_id($mainfilename = false) {
	elgg_deprecated_notice('elgg_get_calling_plugin_id() is deprecated', 1.9);
	if (!$mainfilename) {
		if ($backtrace = debug_backtrace()) {
			foreach ($backtrace as $step) {
				$file = $step['file'];
				$file = str_replace("\\", "/", $file);
				$file = str_replace("//", "/", $file);
				if (preg_match("/mod\/([a-zA-Z0-9\-\_]*)\/start\.php$/", $file, $matches)) {
					return $matches[1];
				}
			}
		}
	} else {
		//@todo this is a hack -- plugins do not have to match their page handler names!
		if ($handler = get_input('handler', false)) {
			return $handler;
		} else {
			$file = $_SERVER["SCRIPT_NAME"];
			$file = str_replace("\\", "/", $file);
			$file = str_replace("//", "/", $file);
			if (preg_match("/mod\/([a-zA-Z0-9\-\_]*)\//", $file, $matches)) {
				return $matches[1];
			}
		}
	}
	return false;
}

/**
 * Returns the \ElggPlugin entity of the last plugin called.
 *
 * @return ElggPlugin|false
 * @since 1.8.0
 * @access private
 * @deprecated 1.9
 */
function elgg_get_calling_plugin_entity() {
	elgg_deprecated_notice("elgg_get_calling_plugin_entity() is deprecated.", 1.9);
	$plugin_id = elgg_get_calling_plugin_id();

	if ($plugin_id) {
		return elgg_get_plugin_from_id($plugin_id);
	}

	return false;
}

return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	// Register a startup event
	$events->registerHandler('init', 'system', '_export_init', 100);

	/** Register the import hook */
	$hooks->registerHandler("import", "all", "import_entity_plugin_hook", 0);
	$hooks->registerHandler("import", "all", "import_extender_plugin_hook", 2);
	$hooks->registerHandler("import", "all", "import_relationship_plugin_hook", 3);

	/** Register the hook, ensuring entities are serialised first */
	$hooks->registerHandler("export", "all", "export_entity_plugin_hook", 0);
	$hooks->registerHandler("export", "all", "export_annotation_plugin_hook", 2);
	$hooks->registerHandler("export", "all", "export_metadata_plugin_hook", 2);
	$hooks->registerHandler("export", "all", "export_relationship_plugin_hook", 3);

	/** Hook to get certain named bits of volatile data about an entity */
	$hooks->registerHandler('volatile', 'metadata', 'volatile_data_export_plugin_hook');
};
