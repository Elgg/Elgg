<?php
/**
 * Bundle all functions which have been deprecated in Elgg 4.1
 */

/**
 * Get the current Elgg version information
 *
 * @param bool $human_readable Whether to return a human readable version (default: false)
 *
 * @return string|false Depending on success
 * @since 1.9
 * @deprecated 4.1
 */
function elgg_get_version($human_readable = false) {
	
	if ($human_readable) {
		elgg_deprecated_notice(__METHOD__ . ' has been deprecated, use elgg_get_release()', '4.1');
		return elgg_get_release();
	}
	
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated. Do not rely on the version number. Instead use elgg_get_release() to get a release tag.', '4.1');
	return '2017041200';
}

/**
 * Checks if a viewtype falls back to default.
 *
 * @param string $viewtype Viewtype
 *
 * @return boolean
 * @since 1.7.2
 * @deprecated 4.1
 */
function elgg_does_viewtype_fallback($viewtype) {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated.', '4.1');
	return _elgg_services()->views->doesViewtypeFallback($viewtype);
}

/**
 * Registers an entity type and subtype as a public-facing entity that should be shown in search
 *
 * @param string $type    The type of entity (object, site, user, group)
 * @param string $subtype The subtype to register (may be blank)
 *
 * @return bool Depending on success
 * @deprecated 4.1
 */
function elgg_register_entity_type($type, $subtype = null) {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated. Use elgg_entity_enable_capability().', '4.1');
	
	$type = strtolower($type);
	if (!in_array($type, \Elgg\Config::ENTITY_TYPES)) {
		return false;
	}

	if (!empty($subtype)) {
		_elgg_services()->entity_capabilities->setCapability($type, $subtype, 'searchable', true);
	}

	return true;
}

/**
 * Unregisters an entity type and subtype as a public-facing type.
 *
 * @param string $type    The type of entity (object, site, user, group)
 * @param string $subtype The subtype to register (may be blank)
 *
 * @return bool Depending on success
 * @deprecated 4.1
 */
function elgg_unregister_entity_type($type, $subtype = null) {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated. Use elgg_entity_disable_capability().', '4.1');
	
	$type = strtolower($type);
	if (!in_array($type, \Elgg\Config::ENTITY_TYPES)) {
		return false;
	}
	
	if (!empty($subtype)) {
		_elgg_services()->entity_capabilities->setCapability($type, $subtype, 'searchable', false);
	}
	
	return true;
}

/**
 * Returns registered entity types and subtypes
 *
 * @param string $type The type of entity (object, site, user, group) or blank for all
 *
 * @return array|false Depending on whether entities have been registered
 * @deprecated 4.1
 */
function get_registered_entity_types($type = null) {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated. Use elgg_entity_types_with_capability().', '4.1');
		
	$entities = _elgg_services()->entity_capabilities->getTypesWithCapability('searchable');
	if (empty($entities)) {
		return false;
	}
	
	if ($type) {
		$type = strtolower($type);
	}
	
	if (!empty($type) && !isset($entities[$type])) {
		return false;
	}
	
	return empty($type) ? $entities : $entities[$type];
}

/**
 * Returns if the entity type and subtype have been registered with {@link elgg_register_entity_type()}.
 *
 * @param string $type    The type of entity (object, site, user, group)
 * @param string $subtype The subtype (may be blank)
 *
 * @return bool Depending on whether or not the type has been registered
 * @deprecated 4.1
 */
function is_registered_entity_type($type, $subtype = null) {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated. Use elgg_entity_has_capability().', '4.1');
	
	if (empty($subtype)) {
		return true;
	}
	
	return _elgg_services()->entity_capabilities->hasCapability($type, $subtype, 'searchable');
}

/**
 * Get user by persistent login password
 *
 * @param string $hash Hash of the persistent login password
 *
 * @return \ElggUser
 * @deprecated 4.1
 */
function get_user_by_code($hash) {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated. Use elgg_get_user_by_persistent_token().', '4.1');
	
	return _elgg_services()->persistentLogin->getUserFromHash($hash);
}
