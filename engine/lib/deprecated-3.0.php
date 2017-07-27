<?php

/**
 * Removes a config setting.
 *
 * @note Internal: These settings are stored in the dbprefix_config table and read
 * during system boot into $CONFIG.
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
 * @internal These settings are stored in the dbprefix_config table and read
 * during system boot into $CONFIG.
 *
 * @internal The value is serialized so we maintain type information.
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
 * @internal These settings are stored in the dbprefix_config table and read
 * during system boot into $CONFIG.
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
 * Gets entities based upon attributes in secondary tables.
 * Also accepts all options available to elgg_get_entities(),
 * elgg_get_entities_from_metadata(), and elgg_get_entities_from_relationship().
 *
 * @warning requires that the entity type be specified and there can only be one
 * type.
 *
 * @see elgg_get_entities
 * @see elgg_get_entities_from_metadata
 * @see elgg_get_entities_from_relationship
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
 * @deprecated
 */
function elgg_get_entities_from_attributes(array $options = array()) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use elgg_get_entities_from_metadata.', '3.0.0');
	
	if (isset($options['attribute_name_value_pairs'])) {
		$options['metadata_name_value_pairs'] = $options['attribute_name_value_pairs'];
		unset($options['attribute_name_value_pairs']);
	}
	
	if (isset($options['attribute_name_value_pairs_operator'])) {
		$options['metadata_name_value_pairs_operator'] = $options['attribute_name_value_pairs_operator'];
		unset($options['attribute_name_value_pairs_operator']);
	}
	
	return _elgg_services()->relationshipsTable->getEntities($options);
}

/**
 * Sets the last action time of the given user to right now.
 *
 * @param int $user_guid The user GUID
 * @return void
 * @deprecated
 */
function set_last_action($user_guid) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \ElggUser->setLastAction().', '3.0.0');
	
	$user = get_user($user_guid);
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
 * @deprecated
 */
function set_last_login($user_guid) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \ElggUser->setLastLogin().', '3.0.0');
	
	$user = get_user($user_guid);
	if (!$user) {
		return;
	}
	$user->setLastLogin();
}

/**
 * Ban a user
 *
 * @param int    $user_guid The user guid
 * @param string $reason    A reason
 *
 * @return bool
 * @deprecated
 */
function ban_user($user_guid, $reason = "") {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \ElggUser->ban().', '3.0.0');
	
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
 * @deprecated
 */
function unban_user($user_guid) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \ElggUser->unban().', '3.0.0');
	
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
 * @deprecated
 */
function make_user_admin($user_guid) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \ElggUser->makeAdmin().', '3.0.0');
	
	$user = get_user($user_guid);
	if (empty($user)) {
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
 * @deprecated
 */
function remove_user_admin($user_guid) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use \ElggUser->removeAdmin().', '3.0.0');
	
	$user = get_user($user_guid);
	if (empty($user)) {
		return false;
	}
	return $user->removeAdmin();
}