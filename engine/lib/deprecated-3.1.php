<?php

/**
 * Delete a directory and all its contents
 *
 * @param string $directory Directory to delete
 *
 * @return bool
 *
 * @deprecated 3.1 Use elgg_delete_directory()
 */
function delete_directory($directory) {
	
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use elgg_delete_directory().', '3.1');
	
	if (!is_string($directory)) {
		return false;
	}
	
	return elgg_delete_directory($directory);
}

/**
 * Register a JavaScript file for inclusion
 *
 * This function handles adding JavaScript to a web page. If multiple
 * calls are made to register the same JavaScript file based on the $id
 * variable, only the last file is included. This allows a plugin to add
 * JavaScript from a view that may be called more than once. It also handles
 * more than one plugin adding the same JavaScript.
 *
 * jQuery plugins often have filenames such as jquery.rating.js. A best practice
 * is to base $name on the filename: "jquery.rating". It is recommended to not
 * use version numbers in the name.
 *
 * The JavaScript files can be local to the server or remote (such as
 * Google's CDN).
 *
 * @note Since 2.0, scripts with location "head" will also be output in the footer, but before
 *       those with location "footer".
 *
 * @param string $name     An identifier for the JavaScript library
 * @param string $url      URL of the JavaScript file
 * @param string $location Page location: head or footer. (default: head)
 * @param int    $priority Priority of the JS file (lower numbers load earlier)
 *
 * @return bool
 * @since 1.8.0
 *
 * @deprecated 3.1 Use AMD modules and elgg_require_js()
 */
function elgg_register_js($name, $url, $location = 'head', $priority = null) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use AMD modules and elgg_require_js().', '3.1');
	
	return elgg_register_external_file('js', $name, $url, $location, $priority);
}

/**
 * Load a JavaScript resource on this page
 *
 * This must be called before elgg_view_page(). It can be called before the
 * script is registered. If you do not want a script loaded, unregister it.
 *
 * @param string $name Identifier of the JavaScript resource
 *
 * @return void
 * @since 1.8.0
 *
 * @deprecated 3.1 Use AMD modules and elgg_require_js()
 */
function elgg_load_js($name) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use AMD modules and elgg_require_js().', '3.1');
	
	elgg_load_external_file('js', $name);
}

/**
 * Unregister a JavaScript file
 *
 * @param string $name The identifier for the JavaScript library
 *
 * @return bool
 * @since 1.8.0
 *
 * @deprecated 3.1
 */
function elgg_unregister_js($name) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated.', '3.1');
	
	return elgg_unregister_external_file('js', $name);
}

/**
 * Register a CSS file for inclusion in the HTML head
 *
 * @param string $name     An identifier for the CSS file
 * @param string $url      URL of the CSS file
 * @param int    $priority Priority of the CSS file (lower numbers load earlier)
 *
 * @return bool
 * @since 1.8.0
 *
 * @deprecated 3.1 Use elgg_require_css()
 */
function elgg_register_css($name, $url, $priority = null) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use elgg_require_css().', '3.1');
	
	return elgg_register_external_file('css', $name, $url, 'head', $priority);
}

/**
 * Unregister a CSS file
 *
 * @param string $name The identifier for the CSS file
 *
 * @return bool
 * @since 1.8.0
 *
 * @deprecated 3.1
 */
function elgg_unregister_css($name) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated.', '3.1');
	
	return elgg_unregister_external_file('css', $name);
}

/**
 * Load a CSS file for this page
 *
 * This must be called before elgg_view_page(). It can be called before the
 * CSS file is registered. If you do not want a CSS file loaded, unregister it.
 *
 * @param string $name Identifier of the CSS file
 *
 * @return void
 * @since 1.8.0
 *
 * @deprecated 3.1 Use elgg_require_css()
 */
function elgg_load_css($name) {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use elgg_require_css().', '3.1');
	
	elgg_load_external_file('css', $name);
}

/**
 * Checks if $entity is an \ElggEntity and optionally for type and subtype.
 *
 * @tip Use this function in actions and views to check that you are dealing
 * with the correct type of entity.
 *
 * @param mixed  $entity  Entity
 * @param string $type    Entity type
 * @param string $subtype Entity subtype
 *
 * @return bool
 * @since 1.8.0
 *
 * @deprecated 3.1 Use PHP instanceof type operator
 */
function elgg_instanceof($entity, $type = null, $subtype = null) {
	elgg_deprecated_notice(__METHOD__ . ' is deprecated. Use PHP instanceof type operator.', '3.1');
	
	$return = ($entity instanceof \ElggEntity);

	if ($type) {
		/* @var \ElggEntity $entity */
		$return = $return && ($entity->getType() == $type);
	}

	if ($subtype) {
		$return = $return && ($entity->getSubtype() == $subtype);
	}

	return $return;
}

/**
 * Check if the given user has full access.
 *
 * @todo: Will always return full access if the user is an admin.
 *
 * @param int $user_guid The user to check
 *
 * @return bool
 * @since 1.7.1
 *
 * @deprecated 3.1 Use ElggUser::isAdmin()
 */
function elgg_is_admin_user($user_guid) {
	elgg_deprecated_notice(__METHOD__ . ' is deprecated. Use ElggUser::isAdmin().', '3.1');
	
	$user_guid = (int) $user_guid;

	$entity = get_user($user_guid);
	if (!$entity) {
		return false;
	}
	
	return $entity->isAdmin();
}

/**
 * Return a string of access_ids for $user_guid appropriate for inserting into an SQL IN clause.
 *
 * @uses get_access_array
 *
 * @see get_access_array()
 *
 * @param int  $user_guid User ID; defaults to currently logged in user
 * @param int  $ignored   Ignored parameter
 * @param bool $flush     If set to true, will refresh the access list from the
 *                        database rather than using this function's cache.
 *
 * @return string A list of access collections suitable for using in an SQL call
 * @internal
 *
 * @deprecated 3.1 Use get_access_array()
 */
function get_access_list($user_guid = 0, $ignored = 0, $flush = false) {
	elgg_deprecated_notice(__METHOD__ . ' is deprecated. Use get_access_array().', '3.1');
	
	return _elgg_services()->accessCollections->getAccessList($user_guid, $flush);
}

/**
 * Updates the membership in an access collection.
 *
 * @warning Expects a full list of all members that should
 * be part of the access collection
 *
 * @note This will run all hooks associated with adding or removing
 * members to access collections.
 *
 * @param int   $collection_id The ID of the collection.
 * @param array $members       Array of member GUIDs
 *
 * @return bool
 * @see add_user_to_access_collection()
 * @see remove_user_from_access_collection()
 *
 * @deprecated 3.1
 */
function update_access_collection($collection_id, $members) {
	elgg_deprecated_notice(__METHOD__ . ' is deprecated.', '3.1');
	
	return _elgg_services()->accessCollections->update($collection_id, $members);
}

/**
 * Returns a list of files in $directory.
 *
 * Only returns files.  Does not recurse into subdirs.
 *
 * @param string $directory  Directory to look in
 * @param array  $exceptions Array of filenames to ignore
 * @param array  $list       Array of files to append to
 * @param mixed  $extensions Array of extensions to allow, null for all. Use a dot: array('.php').
 *
 * @return array Filenames in $directory, in the form $directory/filename.
 *
 * @deprecated 3.1 Use a PHP directory iterator
 */
function elgg_get_file_list($directory, $exceptions = [], $list = [], $extensions = null) {
	elgg_deprecated_notice(__METHOD__ . ' is deprecated. Use a PHP directory iterator.', '3.1');
	
	$directory = \Elgg\Project\Paths::sanitize($directory);
	if ($handle = opendir($directory)) {
		while (($file = readdir($handle)) !== false) {
			if (!is_file($directory . $file) || in_array($file, $exceptions)) {
				continue;
			}

			if (is_array($extensions)) {
				if (in_array(strrchr($file, '.'), $extensions)) {
					$list[] = $directory . $file;
				}
			} else {
				$list[] = $directory . $file;
			}
		}
		closedir($handle);
	}

	return $list;
}

/**
 * Counts the number of messages, either globally or in a particular register
 *
 * @param string $register Optionally, the register
 *
 * @return integer The number of messages
 *
 * @deprecated 3.1 Use elgg()->system_messages->count()
 */
function count_messages($register = "") {
	elgg_deprecated_notice(__METHOD__ . ' is deprecated. Use elgg()->system_messages->count().', '3.1');
	
	return elgg()->system_messages->count($register);
}

/**
 * Sorts a 3d array by specific element.
 *
 * @warning Will re-index numeric indexes.
 *
 * @note This operates the same as the built-in sort functions.
 * It sorts the array and returns a bool for success.
 *
 * Do this: elgg_sort_3d_array_by_value($my_array);
 * Not this: $my_array = elgg_sort_3d_array_by_value($my_array);
 *
 * @param array  $array      Array to sort
 * @param string $element    Element to sort by
 * @param int    $sort_order PHP sort order {@link http://us2.php.net/array_multisort}
 * @param int    $sort_type  PHP sort type {@link http://us2.php.net/sort}
 *
 * @return bool
 *
 * @deprecated 3.1
 */
function elgg_sort_3d_array_by_value(&$array, $element, $sort_order = SORT_ASC, $sort_type = SORT_LOCALE_STRING) {
	elgg_deprecated_notice(__METHOD__ . ' is deprecated.', '3.1');
	
	$sort = [];

	foreach ($array as $v) {
		if (isset($v[$element])) {
			$sort[] = strtolower($v[$element]);
		} else {
			$sort[] = null;
		}
	};

	return array_multisort($sort, $sort_order, $sort_type, $array);
}

/**
 * Return the state of a php.ini setting as a bool
 *
 * @warning Using this on ini settings that are not boolean
 * will be inaccurate!
 *
 * @param string $ini_get_arg The INI setting
 *
 * @return bool Depending on whether it's on or off
 *
 * @deprecated 3.1
 */
function ini_get_bool($ini_get_arg) {
	elgg_deprecated_notice(__METHOD__ . ' is deprecated.', '3.1');
	
	$temp = strtolower(ini_get($ini_get_arg));

	if ($temp == '1' || $temp == 'on' || $temp == 'true') {
		return true;
	}
	return false;
}

/**
 * Returns true is string is not empty, false, or null.
 *
 * Function to be used in array_filter which returns true if $string is not null.
 *
 * @param string $string The string to test
 *
 * @return bool
 *
 * @deprecated 3.1
 */
function is_not_null($string) {
	elgg_deprecated_notice(__METHOD__ . ' is deprecated.', '3.1');
	
	if (($string === '') || ($string === false) || ($string === null)) {
		return false;
	}

	return true;
}

/**
 * Enable an entity.
 *
 * @param int  $guid      GUID of entity to enable
 * @param bool $recursive Recursively enable all entities disabled with the entity?
 *
 * @return bool
 * @since 1.9.0
 *
 * @deprecated 3.1 Use ElggEntity::enable()
 */
function elgg_enable_entity($guid, $recursive = true) {
	elgg_deprecated_notice(__METHOD__ . ' is deprecated. Use ElggEntity::enable().', '3.1');
	
	return _elgg_services()->entityTable->enable($guid, $recursive);
}

/**
 * Detect the current system/user language or false.
 *
 * @return string The language code (eg "en") or false if not set
 *
 * @deprecated 3.1 Use get_current_language()
 */
function get_language() {
	elgg_deprecated_notice(__METHOD__ . ' is deprecated. Use get_current_language().', '3.1');
	
	return elgg()->translator->getCurrentLanguage();
}

/**
 * Return the number of users registered in the system.
 *
 * @param bool $show_deactivated Count not enabled users?
 *
 * @return int
 *
 * @deprecated 3.1 Use elgg_count_entities()
 */
function get_number_users($show_deactivated = false) {
	elgg_deprecated_notice(__METHOD__ . ' is deprecated. Use elgg_count_entities().', '3.1');
	
	$where = new \Elgg\Database\Clauses\EntityWhereClause();
	$where->type_subtype_pairs = [
		'user' => null,
	];

	if ($show_deactivated) {
		$where->use_enabled_clause = false;
	}

	$select = \Elgg\Database\Select::fromTable('entities', 'e');
	$select->select('COUNT(DISTINCT e.guid) AS count');
	$select->addClause($where, 'e');

	$result = _elgg_services()->db->getDataRow($select);
	if (!empty($result)) {
		return (int) $result->count;
	}

	return 0;
}

/**
 * Disables all of a user's entities
 *
 * @param int $owner_guid The owner GUID
 *
 * @return bool Depending on success
 *
 * @deprecated 3.1
 */
function disable_user_entities($owner_guid) {
	elgg_deprecated_notice(__METHOD__ . ' is deprecated.', '3.1');
	
	try {
		$entity = get_entity($owner_guid);
		if (!$entity) {
			return false;
		}
		return _elgg_services()->entityTable->disableEntities($entity);
	} catch (DatabaseException $ex) {
		elgg_log($ex, 'ERROR');

		return false;
	}
}

/**
 * Auto-registers views from a location.
 *
 * @note Views in plugin/views/ are automatically registered for active plugins.
 * Plugin authors would only need to call this if optionally including
 * an entire views structure.
 *
 * @param string $view_base Optional The base of the view name without the view type.
 * @param string $folder    Required The folder to begin looking in
 * @param string $ignored   This argument is ignored
 * @param string $viewtype  The type of view we're looking at (default, rss, etc)
 *
 * @return bool returns false if folder can't be read
 * @since 1.7.0
 * @see elgg_set_view_location()
 * @internal
 *
 * @deprecated 3.1
 */
function autoregister_views($view_base, $folder, $ignored, $viewtype) {
	elgg_deprecated_notice(__METHOD__ . ' is deprecated.', '3.1');
	
	return _elgg_services()->views->autoregisterViews($view_base, $folder, $viewtype);
}

/**
 * Show or hide disabled entities.
 *
 * @param bool $show_hidden Show disabled entities.
 * @return bool
 *
 * @deprecated 3.1 Use elgg_call() with ELGG_SHOW_DISABLED_ENTITIES flag
 */
function access_show_hidden_entities($show_hidden) {
	elgg_deprecated_notice(__METHOD__ . ' is deprecated. Use elgg_call() with ELGG_SHOW_DISABLED_ENTITIES flag.', '3.1');
	
	elgg()->session->setDisabledEntityVisibility($show_hidden);
}

/**
 * Set if Elgg's access system should be ignored.
 *
 * The access system will not return entities in any getter functions if the
 * user doesn't have access. This removes this restriction.
 *
 * When the access system is being ignored, all checks for create, retrieve,
 * update, and delete should pass. This affects all the canEdit() and related
 * methods.
 *
 * @tip Use this to access entities in automated scripts
 * when no user is logged in.
 *
 * @note Internal: The access override is checked in elgg_override_permissions(). It is
 * registered for the 'permissions_check' hooks to override the access system for
 * the canEdit() and canWriteToContainer() methods.
 *
 * @note Internal: This clears the access cache.
 *
 * @note Internal: For performance reasons this is done at the database access clause level.
 *
 * @param bool $ignore If true, disables all access checks.
 *
 * @return bool Previous ignore_access setting.
 * @since 1.7.0
 * @see elgg_get_ignore_access()
 *
 * @deprecated 3.1 Use elgg_call() with ELGG_IGNORE_ACCESS flag
 */
function elgg_set_ignore_access($ignore = true) {
	elgg_deprecated_notice(__METHOD__ . ' is deprecated. Use elgg_call() with ELGG_IGNORE_ACCESS flag.', '3.1');
	
	return elgg()->session->setIgnoreAccess($ignore);
}
