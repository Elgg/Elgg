<?php
/**
 * Lists all function deprecated in Elgg 3.3
 */

/**
 * Deletes all cached views in the simplecache
 *
 * @return bool
 * @since 1.7.4
 * @deprecated 3.3
 */
function elgg_invalidate_simplecache() {
	elgg_deprecated_notice(__METHOD__ . ' is deprecated. Use elgg_clear_caches()', '3.3');
	
	_elgg_services()->simpleCache->clear();
}

/**
 * Flush all the registered caches
 *
 * @return void
 * @since 1.11
 * @deprecated 3.3 use elgg_clear_caches()
 */
function elgg_flush_caches() {
	// this event sequence could take while, make sure there is no timeout
	set_time_limit(0);
	
	elgg_invalidate_caches();
	elgg_clear_caches();
	
	_elgg_services()->events->triggerDeprecatedSequence(
		'cache:flush',
		'system',
		null,
		null,
		"The 'cache:flush' sequence has been deprecated, please use 'cache:clear'.",
		'3.3'
	);
}

/**
 * Queue a query for running during shutdown that writes to the database
 *
 * @param string   $query    The query to execute
 * @param callable $callback The optional callback for processing. The callback will receive a
 *                           \Doctrine\DBAL\Driver\Statement object
 * @param array    $params   Query params. E.g. [1, 'steve'] or [':id' => 1, ':name' => 'steve']
 *
 * @return boolean
 * @deprecated 3.3 use elgg()->db->registerDelayedQuery()
 */
function execute_delayed_write_query($query, $callback = null, array $params = []) {
	elgg_deprecated_notice(__METHOD__ . ' is deprecated. Use elgg()->db->registerDelayedQuery()', '3.3');
	
	return _elgg_services()->db->registerDelayedQuery($query, 'write', $callback, $params);
}

/**
 * Queue a query for running during shutdown that reads from the database
 *
 * @param string   $query    The query to execute
 * @param callable $callback The optional callback for processing. The callback will receive a
 *                           \Doctrine\DBAL\Driver\Statement object
 * @param array    $params   Query params. E.g. [1, 'steve'] or [':id' => 1, ':name' => 'steve']
 *
 * @return boolean
 * @deprecated 3.3 use elgg()->db->registerDelayedQuery()
 */
function execute_delayed_read_query($query, $callback = null, array $params = []) {
	elgg_deprecated_notice(__METHOD__ . ' is deprecated. Use elgg()->db->registerDelayedQuery()', '3.3');
	
	return _elgg_services()->db->registerDelayedQuery($query, 'read', $callback, $params);
}

/**
 * Runs a full database script from disk.
 *
 * The file specified should be a standard SQL file as created by
 * mysqldump or similar.  Statements must be terminated with ;
 * and a newline character (\n or \r\n) with only one statement per line.
 *
 * The special string 'prefix_' is replaced with the database prefix
 * as defined in {@link $CONFIG->dbprefix}.
 *
 * @warning Errors do not halt execution of the script.  If a line
 * generates an error, the error message is saved and the
 * next line is executed.  After the file is run, any errors
 * are displayed as a {@link DatabaseException}
 *
 * @param string $scriptlocation The full path to the script
 *
 * @return void
 * @throws DatabaseException
 * @deprecated 3.3
 */
function run_sql_script($scriptlocation) {
	elgg_deprecated_notice(__METHOD__ . ' is deprecated.', '3.3');
	
	_elgg_services()->db->runSqlScript($scriptlocation);
}

/**
 * Enable the MySQL query cache
 *
 * @return void
 *
 * @since 2.0.0
 * @deprecated 3.3
 */
function elgg_enable_query_cache() {
	elgg_deprecated_notice(__METHOD__ . ' is deprecated.', '3.3');
	
	_elgg_services()->queryCache->enable();
}

/**
 * Disable the MySQL query cache
 *
 * @note Elgg already manages the query cache sensibly, so you probably don't need to use this.
 *
 * @return void
 *
 * @since 2.0.0
 * @deprecated 3.3
 */
function elgg_disable_query_cache() {
	elgg_deprecated_notice(__METHOD__ . ' is deprecated.', '3.3');
	
	_elgg_services()->queryCache->disable();
}

/**
 * Check if a menu item has been registered
 *
 * @param string $menu_name The name of the menu
 * @param string $item_name The unique identifier for this menu item
 *
 * @return bool
 * @since 1.8.0
 * @deprecated 3.3
 */
function elgg_is_menu_item_registered($menu_name, $item_name) {
	elgg_deprecated_notice(__METHOD__ . ' is deprecated.', '3.3');
	
	$menus = _elgg_config()->menus;
	if (empty($menus)) {
		return false;
	}
	
	if (!isset($menus[$menu_name])) {
		return false;
	}
	
	foreach ($menus[$menu_name] as $menu_object) {
		/* @var \ElggMenuItem $menu_object */
		if ($menu_object->getName() == $item_name) {
			return true;
		}
	}
	
	return false;
}

/**
 * Get a menu item registered for a menu
 *
 * @param string $menu_name The name of the menu
 * @param string $item_name The unique identifier for this menu item
 *
 * @return ElggMenuItem|null
 * @since 1.9.0
 * @deprecated 3.3
 */
function elgg_get_menu_item($menu_name, $item_name) {
	elgg_deprecated_notice(__METHOD__ . ' is deprecated.', '3.3');
	
	$menus = _elgg_config()->menus;
	if (empty($menus)) {
		return null;
	}
	
	if (!isset($menus[$menu_name])) {
		return null;
	}
	
	foreach ($menus[$menu_name] as $index => $menu_object) {
		/* @var \ElggMenuItem $menu_object */
		if ($menu_object->getName() == $item_name) {
			return $menus[$menu_name][$index];
		}
	}
	
	return null;
}

/**
 * Converts an associative array into a string of well-formed HTML/XML attributes
 * Returns a concatenated string of HTML attributes to be inserted into a tag (e.g., <tag $attrs>)
 *
 * @param array $attrs Attributes
 *                     An array of attribute => value pairs
 *                     Attribute value can be a scalar value, an array of scalar values, or true
 *                     <code>
 *                     $attrs = array(
 *                         'class' => ['elgg-input', 'elgg-input-text'], // will be imploded with spaces
 *                         'style' => ['margin-left:10px;', 'color: #666;'], // will be imploded with spaces
 *                         'alt' => 'Alt text', // will be left as is
 *                         'disabled' => true, // will be converted to disabled="disabled"
 *                         'data-options' => json_encode(['foo' => 'bar']), // will be output as an escaped JSON string
 *                         'batch' => <\ElggBatch>, // will be ignored
 *                         'items' => [<\ElggObject>], // will be ignored
 *                     );
 *                     </code>
 *
 * @return string
 *
 * @see elgg_format_element()
 * @deprecated 3.3 Use elgg_format_element()
 */
function elgg_format_attributes(array $attrs = []) {
	elgg_deprecated_notice(__METHOD__ . ' is deprecated. Use elgg_format_element()', '3.3');
	
	return _elgg_services()->html_formatter->formatAttributes($attrs);
}

/**
 * List all views in a viewtype
 *
 * @param string $viewtype Viewtype
 *
 * @return string[]
 *
 * @since 2.0
 * @deprecated 3.3
 */
function elgg_list_views($viewtype = 'default') {
	elgg_deprecated_notice(__METHOD__ . ' is deprecated.', '3.3');
	
	return _elgg_services()->views->listViews($viewtype);
}

/**
 * Unsets all plugin settings for a plugin.
 *
 * @param string $plugin_id The plugin ID (Required)
 *
 * @return bool
 * @since 1.8.0
 * @deprecated 3.3 use \ElggPlugin::unsetAllSettings()
 */
function elgg_unset_all_plugin_settings($plugin_id) {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated, use \ElggPlugin::unsetAllSettings()', '3.3');
	
	$plugin = _elgg_services()->plugins->get($plugin_id);
	if (!$plugin) {
		return false;
	}
	
	return $plugin->unsetAllSettings();
}

/**
 * Returns the category of a file from its MIME type
 *
 * @param string $mime_type The MIME type
 *
 * @return string 'document', 'audio', 'video', or 'general' if the MIME type was unrecognized
 * @since 1.10
 * @deprecated 3.3 use elgg()->mimetype->getSimpleType()
 */
function elgg_get_file_simple_type($mime_type) {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated, use elgg()->mimetype->getSimpleType()', '3.3');
	
	return elgg()->mimetype->getSimpleType($mime_type);
}

/**
 * Display a plugin-specified rendered list of annotations for an entity.
 *
 * This displays the output of functions registered to the entity:annotation,
 * $entity_type plugin hook.
 *
 * This is called automatically by the framework from {@link elgg_view_entity()}
 *
 * @param \ElggEntity $entity    Entity
 * @param bool        $full_view Display full view?
 *
 * @return mixed string or false on failure
 * @deprecated 3.3
 */
function elgg_view_entity_annotations(\ElggEntity $entity, $full_view = true) {
	
	$entity_type = $entity->getType();
	
	return elgg_trigger_deprecated_plugin_hook('entity:annotate', $entity_type,
		[
			'entity' => $entity,
			'full_view' => $full_view,
		],
		null,
		'Using the "entity:annotate" hook to add annotations to the view of a full entity is deprecated.',
		'3.3'
	);
}

/**
 * Returns an ordered array of hook handlers registered for $hook and $type.
 *
 * @param string $hook Hook name
 * @param string $type Hook type
 *
 * @return array
 *
 * @since 2.0.0
 *
 * @deprecated 3.3 Use elgg()->hooks->getOrderedHandlers()
 */
function elgg_get_ordered_hook_handlers($hook, $type) {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated. Use elgg()->hooks->getOrderedHandlers()', '3.3');
	
	return elgg()->hooks->getOrderedHandlers($hook, $type);
}

/**
 * Returns an ordered array of event handlers registered for $event and $type.
 *
 * @param string $event Event name
 * @param string $type  Object type
 *
 * @return array
 *
 * @since 2.0.0
 *
 * @deprecated 3.3 use elgg()->events->getOrderedHandlers()
 */
function elgg_get_ordered_event_handlers($event, $type) {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated. Use elgg()->events->getOrderedHandlers()', '3.3');
	
	return elgg()->events->getOrderedHandlers($event, $type);
}

/**
 * Generate an action token.
 *
 * Action tokens are based on timestamps as returned by {@link time()}.
 * They are valid for one hour.
 *
 * Action tokens should be passed to all actions name __elgg_ts and __elgg_token.
 *
 * @warning Action tokens are required for all actions.
 *
 * @param int $timestamp Unix timestamp
 *
 * @see @elgg_view input/securitytoken
 * @see @elgg_view input/form
 *
 * @return string|false
 *
 * @deprecated use elgg()->csrf->generateActionToken()
 */
function generate_action_token($timestamp) {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated. Use elgg()->csrf->generateActionToken()', '3.3');
	
	return elgg()->csrf->generateActionToken($timestamp);
}

/**
 * Wrapper function for mb_split(). Falls back to split() if
 * mb_split() isn't available.  Parameters are passed to the
 * wrapped function in the same order they are passed to this
 * function.
 *
 * @return string
 * @since 1.7.0
 * @deprecated
 */
function elgg_split() {
	elgg_deprecated_notice(__METHOD__ . ' has been deprecated', '3.3');
	
	$args = func_get_args();
	if (is_callable('mb_split')) {
		return call_user_func_array('mb_split', $args);
	}
	return call_user_func_array('split', $args);
}
