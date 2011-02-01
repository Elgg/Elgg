<?php
/**
 * @deprecated 1.6
 */
function delete_group_entity($guid) {
	elgg_deprecated_notice("delete_group_entity has been deprecated", 1.6);

	// Always return that we have deleted one row in order to not break existing code.
	return 1;
}


/**#@+
 * @deprecated 1.7
 */

function get_entities_from_access_id($collection_id, $entity_type = "", $entity_subtype = "", $owner_guid = 0, $limit = 10, $offset = 0, $order_by = "", $site_guid = 0, $count = false) {
	// log deprecated warning
	elgg_deprecated_notice('get_entities_from_access_id() was deprecated by elgg_get_entities()', 1.7);

	if (!$collection_id) {
		return FALSE;
	}

	// build the options using given parameters
	$options = array();
	$options['limit'] = $limit;
	$options['offset'] = $offset;
	$options['count'] = $count;

	if ($entity_type) {
		$options['type'] = sanitise_string($entity_type);
	}

	if ($entity_subtype) {
		$options['subtype'] = $entity_subtype;
	}

	if ($site_guid) {
		$options['site_guid'] = $site_guid;
	}

	if ($order_by) {
		$options['order_by'] = sanitise_string("e.time_created, $order_by");
	}

	if ($owner_guid) {
		if (is_array($owner_guid)) {
			$options['owner_guids'] = $owner_guid;
		} else {
			$options['owner_guid'] = $owner_guid;
		}
	}

	if ($site_guid) {
		$options['site_guid'] = $site_guid;
	}

	$options['access_id'] = $collection_id;

	return elgg_get_entities_from_access_id($options);
}

function get_entities_from_access_collection($collection_id, $entity_type = "", $entity_subtype = "", $owner_guid = 0, $limit = 10, $offset = 0, $order_by = "", $site_guid = 0, $count = false) {

	elgg_deprecated_notice('get_entities_from_access_collection() was deprecated by elgg_get_entities()', 1.7);

	return get_entities_from_access_id($collection_id, $entity_type, $entity_subtype, $owner_guid, $limit, $offset, $order_by, $site_guid, $count);
}

function get_entities($type = "", $subtype = "", $owner_guid = 0, $order_by = "", $limit = 10, $offset = 0, $count = false, $site_guid = 0, $container_guid = null, $timelower = 0, $timeupper = 0) {

	elgg_deprecated_notice('get_entities() was deprecated by elgg_get_entities().', 1.7);

	// rewrite owner_guid to container_guid to emulate old functionality
	if ($owner_guid != "") {
		if (is_null($container_guid)) {
			$container_guid = $owner_guid;
			$owner_guid = NULL;
		}
	}

	$options = array();
	if ($type) {
		if (is_array($type)) {
			$options['types'] = $type;
		} else {
			$options['type'] = $type;
		}
	}

	if ($subtype) {
		if (is_array($subtype)) {
			$options['subtypes'] = $subtype;
		} else {
			$options['subtype'] = $subtype;
		}
	}

	if ($owner_guid) {
		if (is_array($owner_guid)) {
			$options['owner_guids'] = $owner_guid;
		} else {
			$options['owner_guid'] = $owner_guid;
		}
	}

	if ($order_by) {
		$options['order_by'] = $order_by;
	}

	// need to pass 0 for all option
	$options['limit'] = $limit;

	if ($offset) {
		$options['offset'] = $offset;
	}

	if ($count) {
		$options['count'] = $count;
	}

	if ($site_guid) {
		$options['site_guids'] = $site_guid;
	}

	if ($container_guid) {
		$options['container_guids'] = $container_guid;
	}

	if ($timeupper) {
		$options['created_time_upper'] = $timeupper;
	}

	if ($timelower) {
		$options['created_time_lower'] = $timelower;
	}

	$r = elgg_get_entities($options);
	return $r;
}

function list_entities($type = "", $subtype = "", $owner_guid = 0, $limit = 10, $fullview = true, $listtypetoggle = false, $pagination = true) {

	elgg_deprecated_notice('list_entities() was deprecated by elgg_list_entities()!', 1.7);

	$options = array();

	// rewrite owner_guid to container_guid to emulate old functionality
	if ($owner_guid) {
		$options['container_guids'] = $owner_guid;
	}

	if ($type) {
		$options['types'] = $type;
	}

	if ($subtype) {
		$options['subtypes'] = $subtype;
	}

	if ($limit) {
		$options['limit'] = $limit;
	}

	if ($offset = sanitise_int(get_input('offset', null))) {
		$options['offset'] = $offset;
	}

	$options['full_view'] = $fullview;
	$options['list_type_toggle'] = $listtypetoggle;
	$options['pagination'] = $pagination;

	return elgg_list_entities($options);
}

function delete_entities($type = "", $subtype = "", $owner_guid = 0) {
	elgg_deprecated_notice('delete_entities() was deprecated because no one should use it.', 1.7);
	return false;
}

function elgg_validate_action_url($url) {
	elgg_deprecated_notice('elgg_validate_action_url() deprecated by elgg_add_action_tokens_to_url().', '1.7b');

	return elgg_add_action_tokens_to_url($url);
}

function test_ip() {
	elgg_deprecated_notice('test_ip() was removed because of licensing issues.', 1.7);

	return 0;
}

function is_ip_in_array() {
	elgg_deprecated_notice('is_ip_in_array() was removed because of licensing issues.', 1.7);

	return false;
}

function list_registered_entities($owner_guid = 0, $limit = 10, $fullview = true, $listtypetoggle = false, $allowedtypes = true) {

	elgg_deprecated_notice('list_registered_entities() was deprecated by elgg_list_registered_entities().', 1.7);

	$options = array();

	// don't want to send anything if not being used.
	if ($owner_guid) {
		$options['owner_guid'] = $owner_guid;
	}

	if ($limit) {
		$options['limit'] = $limit;
	}

	if ($allowedtypes) {
		$options['allowed_types'] = $allowedtypes;
	}

	// need to send because might be BOOL
	$options['full_view'] = $fullview;
	$options['list_type_toggle'] = $listtypetoggle;

	$options['offset'] = get_input('offset', 0);

	return elgg_list_registered_entities($options);
}

function get_library_files($directory, $exceptions = array(), $list = array()) {
	elgg_deprecated_notice('get_library_files() deprecated by elgg_get_file_list()', 1.7);
	return elgg_get_file_list($directory, $exceptions, $list, array('.php'));
}

function get_entities_from_annotations($entity_type = "", $entity_subtype = "", $name = "", $value = "", $owner_guid = 0, $group_guid = 0, $limit = 10, $offset = 0, $order_by = "asc", $count = false, $timelower = 0, $timeupper = 0) {
	$msg = 'get_entities_from_annotations() is deprecated by elgg_get_entities_from_annotations().';
	elgg_deprecated_notice($msg, 1.7);

	$options = array();

	if ($entity_type) {
		$options['types'] = $entity_type;
	}

	if ($entity_subtype) {
		$options['subtypes'] = $entity_subtype;
	}

	$options['annotation_names'] = $name;

	if ($value) {
		$options['annotation_values'] = $value;
	}

	if ($owner_guid) {
		if (is_array($owner_guid)) {
			$options['annotation_owner_guids'] = $owner_guid;
		} else {
			$options['annotation_owner_guid'] = $owner_guid;
		}
	}

	if ($group_guid) {
		$options['container_guid'] = $group_guid;
	}

	if ($limit) {
		$options['limit'] = $limit;
	}

	if ($offset) {
		$options['offset'] = $offset;
	}

	if ($order_by) {
		$options['order_by'] = "maxtime $order_by";
	}

	if ($count) {
		$options['count'] = $count;
	}

	if ($timelower) {
		$options['annotation_created_time_lower'] = $timelower;
	}

	if ($timeupper) {
		$options['annotation_created_time_upper'] = $timeupper;
	}

	return elgg_get_entities_from_annotations($options);
}

function list_entities_from_annotations($entity_type = "", $entity_subtype = "", $name = "", $value = "", $limit = 10, $owner_guid = 0, $group_guid = 0, $asc = false, $fullview = true, $listtypetoggle = false) {

	$msg = 'list_entities_from_annotations is deprecated by elgg_list_entities_from_annotations';
	elgg_deprecated_notice($msg, 1.8);

	$options = array();

	if ($entity_type) {
		$options['types'] = $entity_type;
	}

	if ($entity_subtype) {
		$options['subtypes'] = $entity_subtype;
	}

	if ($name) {
		$options['annotation_names'] = $name;
	}

	if ($value) {
		$options['annotation_values'] = $value;
	}

	if ($limit) {
		$options['limit'] = $limit;
	}

	if ($owner_guid) {
		$options['annotation_owner_guid'] = $owner_guid;
	}

	if ($group_guid) {
		$options['container_guid'] = $group_guid;
	}

	if ($asc) {
		$options['order_by'] = 'maxtime desc';
	}

	if ($offset = sanitise_int(get_input('offset', null))) {
		$options['offset'] = $offset;
	}

	$options['full_view'] = $fullview;
	$options['list_type_toggle'] = $listtypetoggle;
	$options['pagination'] = $pagination;

	return elgg_list_entities_from_annotations($options);
}

function search_for_group($criteria, $limit = 10, $offset = 0, $order_by = "", $count = false) {
	elgg_deprecated_notice('search_for_group() was deprecated by new search plugin.', 1.7);
	global $CONFIG;

	$criteria = sanitise_string($criteria);
	$limit = (int)$limit;
	$offset = (int)$offset;
	$order_by = sanitise_string($order_by);

	$access = get_access_sql_suffix("e");

	if ($order_by == "") {
		$order_by = "e.time_created desc";
	}

	if ($count) {
		$query = "SELECT count(e.guid) as total ";
	} else {
		$query = "SELECT e.* ";
	}
	$query .= "from {$CONFIG->dbprefix}entities e" . " JOIN {$CONFIG->dbprefix}groups_entity g on e.guid=g.guid where ";

	$query .= "(g.name like \"%{$criteria}%\" or g.description like \"%{$criteria}%\")";
	$query .= " and $access";

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

function search_list_groups_by_name($hook, $user, $returnvalue, $tag) {
	elgg_deprecated_notice('search_list_groups_by_name() was deprecated by new search plugin', 1.7);
	// Change this to set the number of groups that display on the search page
	$threshold = 4;

	$object = get_input('object');

	if (!get_input('offset') && (empty($object) || $object == 'group')) {
		if ($groups = search_for_group($tag, $threshold)) {
			$countgroups = search_for_group($tag, 0, 0, "", true);

			$return = elgg_view('group/search/startblurb', array('count' => $countgroups,
				'tag' => $tag));
			foreach ($groups as $group) {
				$return .= elgg_view_entity($group);
			}
			$vars = array('count' => $countgroups, 'threshold' => $threshold, 'tag' => $tag);
			$return .= elgg_view('group/search/finishblurb', $vars);
			return $return;
		}
	}
}

function list_group_search($tag, $limit = 10) {
	elgg_deprecated_notice('list_group_search() was deprecated by new search plugin.', 1.7);
	$offset = (int)get_input('offset');
	$limit = (int)$limit;
	$count = (int)search_for_group($tag, 10, 0, '', true);
	$entities = search_for_group($tag, $limit, $offset);

	return elgg_view_entity_list($entities, $count, $offset, $limit, $fullview, false);

}

function get_entities_from_metadata($meta_name, $meta_value = "", $entity_type = "", $entity_subtype = "", $owner_guid = 0, $limit = 10, $offset = 0, $order_by = "", $site_guid = 0, $count = FALSE, $case_sensitive = TRUE) {

	elgg_deprecated_notice('get_entities_from_metadata() was deprecated by elgg_get_entities_from_metadata()!', 1.7);

	$options = array();

	$options['metadata_names'] = $meta_name;

	if ($meta_value) {
		$options['metadata_values'] = $meta_value;
	}

	if ($entity_type) {
		$options['types'] = $entity_type;
	}

	if ($entity_subtype) {
		$options['subtypes'] = $entity_subtype;
	}

	if ($owner_guid) {
		if (is_array($owner_guid)) {
			$options['owner_guids'] = $owner_guid;
		} else {
			$options['owner_guid'] = $owner_guid;
		}
	}

	if ($limit) {
		$options['limit'] = $limit;
	}

	if ($offset) {
		$options['offset'] = $offset;
	}

	if ($order_by) {
		$options['order_by'];
	}

	if ($site_guid) {
		$options['site_guid'];
	}

	if ($count) {
		$options['count'] = $count;
	}

	// need to be able to pass false
	$options['metadata_case_sensitive'] = $case_sensitive;

	return elgg_get_entities_from_metadata($options);
}

function get_entities_from_metadata_multi($meta_array, $entity_type = "", $entity_subtype = "", $owner_guid = 0, $limit = 10, $offset = 0, $order_by = "", $site_guid = 0, $count = false, $meta_array_operator = 'and') {

	elgg_deprecated_notice('get_entities_from_metadata_multi() was deprecated by elgg_get_entities_from_metadata()!', 1.7);

	if (!is_array($meta_array) || sizeof($meta_array) == 0) {
		return false;
	}

	$options = array();

	$options['metadata_name_value_pairs'] = $meta_array;

	if ($entity_type) {
		$options['types'] = $entity_type;
	}

	if ($entity_subtype) {
		$options['subtypes'] = $entity_subtype;
	}

	if ($owner_guid) {
		if (is_array($owner_guid)) {
			$options['owner_guids'] = $owner_guid;
		} else {
			$options['owner_guid'] = $owner_guid;
		}
	}

	if ($limit) {
		$options['limit'] = $limit;
	}

	if ($offset) {
		$options['offset'] = $offset;
	}

	if ($order_by) {
		$options['order_by'];
	}

	if ($site_guid) {
		$options['site_guid'];
	}

	if ($count) {
		$options['count'] = $count;
	}

	$options['metadata_name_value_pairs_operator'] = $meta_array_operator;

	return elgg_get_entities_from_metadata($options);
}

function menu_item($menu_name, $menu_url) {
	elgg_deprecated_notice('menu_item() is deprecated by add_submenu_item', 1.7);
	return make_register_object($menu_name, $menu_url);
}

function search_for_object($criteria, $limit = 10, $offset = 0, $order_by = "", $count = false) {
	elgg_deprecated_notice('search_for_object() was deprecated by new search plugin.', 1.7);
	global $CONFIG;

	$criteria = sanitise_string($criteria);
	$limit = (int)$limit;
	$offset = (int)$offset;
	$order_by = sanitise_string($order_by);
	$container_guid = (int)$container_guid;

	$access = get_access_sql_suffix("e");

	if ($order_by == "") {
		$order_by = "e.time_created desc";
	}

	if ($count) {
		$query = "SELECT count(e.guid) as total ";
	} else {
		$query = "SELECT e.* ";
	}
	$query .= "from {$CONFIG->dbprefix}entities e
		join {$CONFIG->dbprefix}objects_entity o on e.guid=o.guid
		where match(o.title,o.description) against ('$criteria') and $access";

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

function search_list_objects_by_name($hook, $user, $returnvalue, $tag) {
	elgg_deprecated_notice('search_list_objects_by_name was deprecated by new search plugin.', 1.7);

	// Change this to set the number of users that display on the search page
	$threshold = 4;

	$object = get_input('object');

	if (!get_input('offset') && (empty($object) || $object == 'user')) {
		if ($users = search_for_user($tag, $threshold)) {
			$countusers = search_for_user($tag, 0, 0, "", true);

			$return = elgg_view('user/search/startblurb', array('count' => $countusers,
				'tag' => $tag));
			foreach ($users as $user) {
				$return .= elgg_view_entity($user);
			}
			$return .= elgg_view('user/search/finishblurb', array('count' => $countusers,
				'threshold' => $threshold, 'tag' => $tag));

			return $return;

		}
	}
}

/**#@-*/

/**#@+
 * @deprecated 1.8
 */

function elgg_get_entity_site_where_sql($table, $site_guids) {
	elgg_deprecated_notice('elgg_get_entity_site_where_sql() is deprecated by elgg_get_guid_based_where_sql().', 1.8);

	return elgg_get_guid_based_where_sql("{$table}.site_guid", $site_guids);
}

function elgg_get_entity_container_where_sql($table, $container_guids) {
	elgg_deprecated_notice('elgg_get_entity_container_where_sql() is deprecated by elgg_get_guid_based_where_sql().', 1.8);

	return elgg_get_guid_based_where_sql("{$table}.container_guid", $container_guids);
}

function elgg_get_entity_owner_where_sql($table, $owner_guids) {
	elgg_deprecated_notice('elgg_get_entity_owner_where_sql() is deprecated by elgg_get_guid_based_where_sql().', 1.8);

	return elgg_get_guid_based_where_sql("{$table}.owner_guid", $owner_guids);
}

function list_entities_from_access_id($access_id, $entity_type = "", $entity_subtype = "", $owner_guid = 0, $limit = 10, $fullview = true, $listtypetoggle = true, $pagination = true) {
	elgg_deprecated_notice("All list_entities* functions were deprecated in 1.8.  Use elgg_list_entities* instead.", 1.8);

	echo elgg_list_entities_from_access_id(array('access_id' => $access_id,
		'types' => $entity_type, 'subtypes' => $entity_subtype, 'owner_guids' => $owner_guid,
		'limit' => $limit, 'full_view' => $fullview, 'list_type_toggle' => $listtypetoggle,
		'pagination' => $pagination,));
}

function register_action($action, $public = false, $filename = "", $admin_only = false) {
	elgg_deprecated_notice("register_action() was deprecated by elgg_register_action()", 1.8);

	if ($admin_only) {
		$access = 'admin';
	} elseif ($public) {
		$access = 'public';
	} else {
		$access = 'logged_in';
	}

	return elgg_register_action($action, $filename, $access);
}

function extend_elgg_admin_page($new_admin_view, $view = 'admin/main', $priority = 500) {
	elgg_deprecated_notice('extend_elgg_admin_page() does nothing. Extend admin views manually.', 1.8);
}

function get_entities_from_annotations_calculate_x($sum = "sum", $entity_type = "", $entity_subtype = "", $name = "", $mdname = '', $mdvalue = '', $owner_guid = 0, $limit = 10, $offset = 0, $orderdir = 'desc', $count = false) {

	$msg = 'get_entities_from_annotations_calculate_x() is deprecated by elgg_get_entities_from_annotation_calculation().';

	elgg_deprecated_notice($msg, 1.8);

	$options = array();

	$options['calculation'] = $sum;

	if ($entity_type) {
		$options['types'] = $entity_type;
	}

	if ($entity_subtype) {
		$options['subtypes'] = $entity_subtype;
	}

	$options['annotation_names'] = $name;

	if ($mdname) {
		$options['metadata_names'] = $mdname;
	}

	if ($mdvalue) {
		$options['metadata_values'] = $mdvalue;
	}

	// original function rewrote this to container guid.
	if ($owner_guid) {
		if (is_array($owner_guid)) {
			$options['container_guids'] = $owner_guid;
		} else {
			$options['container_guid'] = $owner_guid;
		}
	}

	$options['limit'] = $limit;
	$options['offset'] = $offset;

	$options['order_by'] = "calculated $orderdir";

	$options['count'] = $count;

	return elgg_get_entities_from_annotation_calculation($options);
}

function get_entities_from_annotation_count($entity_type = "", $entity_subtype = "", $name = "", $mdname = '', $mdvalue = '', $owner_guid = 0, $limit = 10, $offset = 0, $orderdir = 'desc', $count = false) {

	$msg = 'get_entities_from_annotation_count() is deprecated by elgg_get_entities_from_annotation_calculation().';

	elgg_deprecated_notice($msg, 1.8);

	$options = array();

	$options['calculation'] = 'sum';

	if ($entity_type) {
		$options['types'] = $entity_type;
	}

	if ($entity_subtype) {
		$options['subtypes'] = $entity_subtype;
	}

	$options['annotation_names'] = $name;

	if ($mdname) {
		$options['metadata_names'] = $mdname;
	}

	if ($mdvalue) {
		$options['metadata_values'] = $mdvalue;
	}

	if ($owner_guid) {
		if (is_array($owner_guid)) {
			$options['owner_guids'] = $owner_guid;
		} else {
			$options['owner_guid'] = $owner_guid;
		}
	}

	$options['limit'] = $limit;
	$options['offset'] = $offset;

	$options['order_by'] = "calculated $orderdir";

	$options['count'] = $count;

	return elgg_get_entities_from_annotation_calculation($options);
}

function list_entities_from_annotation_count($entity_type = "", $entity_subtype = "", $name = "", $limit = 10, $owner_guid = 0, $group_guid = 0, $asc = false, $fullview = true, $listtypetoggle = false, $pagination = true, $orderdir = 'desc') {

	$msg = 'list_entities_from_annotation_count() is deprecated by elgg_list_entities_from_annotation_calculation().';

	elgg_deprecated_notice($msg, 1.8);

	$options = array();

	$options['calculation'] = 'sum';

	if ($entity_type) {
		$options['types'] = $entity_type;
	}

	if ($entity_subtype) {
		$options['subtypes'] = $entity_subtype;
	}

	$options['annotation_names'] = $name;

	if ($owner_guid) {
		if (is_array($owner_guid)) {
			$options['owner_guids'] = $owner_guid;
		} else {
			$options['owner_guid'] = $owner_guid;
		}
	}

	$options['full_view'] = $fullview;

	$options['list_type_toggle'] = $listtypetoggle;

	$options['pagination'] = $pagination;

	$options['limit'] = $limit;

	$options['order_by'] = "calculated $orderdir";

	return elgg_get_entities_from_annotation_calculation($options);
}

function get_register($register_name) {
	elgg_deprecated_notice("get_register() has been deprecated", 1.8);
	global $CONFIG;

	if (isset($CONFIG->registers[$register_name])) {
		return $CONFIG->registers[$register_name];
	}

	return false;
}

function events($event = "", $object_type = "", $function = "", $priority = 500, $call = false, $object = null) {

	elgg_deprecated_notice('events() has been deprecated.', 1.8);

	// leaving this here just in case someone was directly calling this internal function
	if (!$call) {
		return elgg_register_event_handler($event, $object_type, $function, $priority);
	} else {
		return trigger_elgg_event($event, $object_type, $object);
	}
}

function register_elgg_event_handler($event, $object_type, $callback, $priority = 500) {
	elgg_deprecated_notice("register_elgg_event_handler() was deprecated by elgg_register_event_handler()", 1.8);
	return elgg_register_event_handler($event, $object_type, $callback, $priority);
}

function unregister_elgg_event_handler($event, $object_type, $callback) {
	elgg_deprecated_notice('unregister_elgg_event_handler => elgg_unregister_event_handler', 1.8);
	elgg_unregister_event_handler($event, $object_type, $callback);
}

function trigger_elgg_event($event, $object_type, $object = null) {
	elgg_deprecated_notice('trigger_elgg_event() was deprecated by elgg_trigger_event()', 1.8);
	return elgg_trigger_event($event, $object_type, $object);
}

function register_plugin_hook($hook, $type, $callback, $priority = 500) {
	elgg_deprecated_notice("register_plugin_hook() was deprecated by elgg_register_plugin_hook_handler()", 1.8);
	return elgg_register_plugin_hook_handler($hook, $type, $callback, $priority);
}

function unregister_plugin_hook($hook, $entity_type, $callback) {
	elgg_deprecated_notice("unregister_plugin_hook() was deprecated by elgg_unregister_plugin_hook_handler()", 1.8);
	elgg_unregister_plugin_hook_handler($hook, $entity_type, $callback);
}

function trigger_plugin_hook($hook, $type, $params = null, $returnvalue = null) {
	elgg_deprecated_notice("trigger_plugin_hook() was deprecated by elgg_trigger_plugin_hook()", 1.8);
	return elgg_trigger_plugin_hook($hook, $type, $params, $returnvalue);
}

function call_gatekeeper($function, $file = "") {
	elgg_deprecated_notice("call_gatekeeper() is neat but pointless", 1.8);
	// Sanity check
	if (!$function) {
		return false;
	}

	// Check against call stack to see if this is being called from the correct location
	$callstack = debug_backtrace();
	$stack_element = false;

	foreach ($callstack as $call) {
		if (is_array($function)) {
			if ((strcmp($call['class'], $function[0]) == 0) && (strcmp($call['function'], $function[1]) == 0)) {
				$stack_element = $call;
			}
		} else {
			if (strcmp($call['function'], $function) == 0) {
				$stack_element = $call;
			}
		}
	}

	if (!$stack_element) {
		return false;
	}

	// If file then check that this it is being called from this function
	if ($file) {
		$mirror = null;

		if (is_array($function)) {
			$mirror = new ReflectionMethod($function[0], $function[1]);
		} else {
			$mirror = new ReflectionFunction($function);
		}

		if ((!$mirror) || (strcmp($file, $mirror->getFileName()) != 0)) {
			return false;
		}
	}

	return true;
}

function callpath_gatekeeper($path, $include_subdirs = true, $strict_mode = false) {
	elgg_deprecated_notice("callpath_gatekeeper() is neat but pointless", 1.8);

	global $CONFIG;

	$path = sanitise_string($path);

	if ($path) {
		$callstack = debug_backtrace();

		foreach ($callstack as $call) {
			$call['file'] = str_replace("\\", "/", $call['file']);

			if ($include_subdirs) {
				if (strpos($call['file'], $path) === 0) {

					if ($strict_mode) {
						$callstack[1]['file'] = str_replace("\\", "/", $callstack[1]['file']);
						if ($callstack[1] === $call) {
							return true;
						}
					} else {
						return true;
					}
				}
			} else {
				if (strcmp($path, $call['file']) == 0) {
					if ($strict_mode) {
						if ($callstack[1] === $call) {
							return true;
						}
					} else {
						return true;
					}
				}
			}

		}
		return false;
	}

	if (isset($CONFIG->debug)) {
		system_message("Gatekeeper'd function called from {$callstack[1]['file']}:" . "{$callstack[1]['line']}\n\nStack trace:\n\n" . print_r($callstack, true));
	}

	return false;
}

function get_objects_in_group($group_guid, $subtype = "", $owner_guid = 0, $site_guid = 0, $order_by = "", $limit = 10, $offset = 0, $count = FALSE) {
	elgg_deprecated_notice("get_objects_in_group was deprected in 1.8.  Use elgg_get_entities() instead", 1.8);

	global $CONFIG;

	if ($subtype === FALSE || $subtype === null || $subtype === 0) {
		return FALSE;
	}

	if ($order_by == "") {
		$order_by = "e.time_created desc";
	}
	$order_by = sanitise_string($order_by);
	$limit = (int)$limit;
	$offset = (int)$offset;
	$site_guid = (int)$site_guid;
	if ($site_guid == 0) {
		$site_guid = $CONFIG->site_guid;
	}

	$container_guid = (int)$group_guid;
	if ($container_guid == 0) {
		$container_guid = elgg_get_page_owner_guid();
	}

	$where = array();

	$where[] = "e.type='object'";

	if (!empty($subtype)) {
		if (!$subtype = get_subtype_id('object', $subtype)) {
			return FALSE;
		}
		$where[] = "e.subtype=$subtype";
	}
	if ($owner_guid != "") {
		if (!is_array($owner_guid)) {
			$owner_guid = (int)$owner_guid;
			$where[] = "e.container_guid = '$owner_guid'";
		} else if (sizeof($owner_guid) > 0) {
			// Cast every element to the owner_guid array to int
			$owner_guid = array_map("sanitise_int", $owner_guid);
			$owner_guid = implode(",", $owner_guid);
			$where[] = "e.container_guid in ({$owner_guid})";
		}
	}
	if ($site_guid > 0) {
		$where[] = "e.site_guid = {$site_guid}";
	}

	if ($container_guid > 0) {
		$where[] = "e.container_guid = {$container_guid}";
	}

	if (!$count) {
		$query = "SELECT * from {$CONFIG->dbprefix}entities e" . " join {$CONFIG->dbprefix}objects_entity o on e.guid=o.guid where ";
	} else {
		$query = "SELECT count(e.guid) as total from {$CONFIG->dbprefix}entities e" . " join {$CONFIG->dbprefix}objects_entity o on e.guid=o.guid where ";
	}
	foreach ($where as $w) {
		$query .= " $w and ";
	}

	// Add access controls
	$query .= get_access_sql_suffix('e');
	if (!$count) {
		$query .= " order by $order_by";

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

function list_entities_groups($subtype = "", $owner_guid = 0, $container_guid = 0, $limit = 10, $fullview = true, $listtypetoggle = true, $pagination = true) {
	elgg_deprecated_notice("list_entities_groups was deprecated in 1.8.  Use elgg_list_entities() instead.", 1.8);
	$offset = (int)get_input('offset');
	$count = get_objects_in_group($container_guid, $subtype, $owner_guid, 0, "", $limit, $offset, true);
	$entities = get_objects_in_group($container_guid, $subtype, $owner_guid, 0, "", $limit, $offset);

	return elgg_view_entity_list($entities, $count, $offset, $limit, $fullview, $listtypetoggle, $pagination);
}

function get_entities_from_metadata_groups($group_guid, $meta_name, $meta_value = "", $entity_type = "", $entity_subtype = "", $owner_guid = 0, $limit = 10, $offset = 0, $order_by = "", $site_guid = 0, $count = false) {
	elgg_deprecated_notice("get_entities_from_metadata_groups was deprecated in 1.8.", 1.8);
	global $CONFIG;

	$meta_n = get_metastring_id($meta_name);
	$meta_v = get_metastring_id($meta_value);

	$entity_type = sanitise_string($entity_type);
	$entity_subtype = get_subtype_id($entity_type, $entity_subtype);
	$limit = (int)$limit;
	$offset = (int)$offset;
	if ($order_by == "") {
		$order_by = "e.time_created desc";
	}
	$order_by = sanitise_string($order_by);
	$site_guid = (int)$site_guid;
	if (is_array($owner_guid)) {
		foreach ($owner_guid as $key => $guid) {
			$owner_guid[$key] = (int)$guid;
		}
	} else {
		$owner_guid = (int)$owner_guid;
	}
	if ($site_guid == 0) {
		$site_guid = $CONFIG->site_guid;
	}

	$container_guid = (int)$group_guid;
	if ($container_guid == 0) {
		$container_guid = elgg_get_page_owner_guid();
	}

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
	if ($container_guid > 0) {
		$where[] = "e.container_guid = {$container_guid}";
	}

	if (is_array($owner_guid)) {
		$where[] = "e.container_guid in (" . implode(",", $owner_guid) . ")";
	} else if ($owner_guid > 0) {
		$where[] = "e.container_guid = {$owner_guid}";
	}

	if (!$count) {
		$query = "SELECT distinct e.* ";
	} else {
		$query = "SELECT count(e.guid) as total ";
	}

	$query .= "from {$CONFIG->dbprefix}entities e" . " JOIN {$CONFIG->dbprefix}metadata m on e.guid = m.entity_guid " . " JOIN {$CONFIG->dbprefix}objects_entity o on e.guid = o.guid where";

	foreach ($where as $w) {
		$query .= " $w and ";
	}

	// Add access controls
	$query .= get_access_sql_suffix("e");

	if (!$count) {
		$query .= " order by $order_by limit $offset, $limit"; // Add order and limit
		return get_data($query, "entity_row_to_elggstar");
	} else {
		if ($row = get_data_row($query)) {
			return $row->total;
		}
	}
	return false;
}

function get_entities_from_metadata_groups_multi($group_guid, $meta_array, $entity_type = "", $entity_subtype = "", $owner_guid = 0, $limit = 10, $offset = 0, $order_by = "", $site_guid = 0, $count = false) {
	elgg_deprecated_notice("get_entities_from_metadata_groups_multi was deprecated in 1.8.", 1.8);

	global $CONFIG;

	if (!is_array($meta_array) || sizeof($meta_array) == 0) {
		return false;
	}

	$where = array();

	$mindex = 1;
	$join = "";
	foreach ($meta_array as $meta_name => $meta_value) {
		$meta_n = get_metastring_id($meta_name);
		$meta_v = get_metastring_id($meta_value);
		$join .= " JOIN {$CONFIG->dbprefix}metadata m{$mindex} on e.guid = m{$mindex}.entity_guid" . " JOIN {$CONFIG->dbprefix}objects_entity o on e.guid = o.guid ";

		if ($meta_name != "") {
			$where[] = "m{$mindex}.name_id='$meta_n'";
		}

		if ($meta_value != "") {
			$where[] = "m{$mindex}.value_id='$meta_v'";
		}

		$mindex++;
	}

	$entity_type = sanitise_string($entity_type);
	$entity_subtype = get_subtype_id($entity_type, $entity_subtype);
	$limit = (int)$limit;
	$offset = (int)$offset;
	if ($order_by == "") {
		$order_by = "e.time_created desc";
	}
	$order_by = sanitise_string($order_by);
	$owner_guid = (int)$owner_guid;

	$site_guid = (int)$site_guid;
	if ($site_guid == 0) {
		$site_guid = $CONFIG->site_guid;
	}

	//$access = get_access_list();

	if ($entity_type != "") {
		$where[] = "e.type = '{$entity_type}'";
	}

	if ($entity_subtype) {
		$where[] = "e.subtype = {$entity_subtype}";
	}

	if ($site_guid > 0) {
		$where[] = "e.site_guid = {$site_guid}";
	}

	if ($owner_guid > 0) {
		$where[] = "e.owner_guid = {$owner_guid}";
	}

	if ($container_guid > 0) {
		$where[] = "e.container_guid = {$container_guid}";
	}

	if ($count) {
		$query = "SELECT count(e.guid) as total ";
	} else {
		$query = "SELECT distinct e.* ";
	}

	$query .= " from {$CONFIG->dbprefix}entities e {$join} where";
	foreach ($where as $w) {
		$query .= " $w and ";
	}
	$query .= get_access_sql_suffix("e"); // Add access controls

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

function list_entities_in_area($lat, $long, $radius, $type = "", $subtype = "", $owner_guid = 0, $limit = 10, $fullview = true, $listtypetoggle = false, $navigation = true) {
	elgg_deprecated_notice('list_entities_in_area() was deprecated. Use elgg_list_entities_from_location()', 1.8);

	$options = array();

	$options['latitude'] = $lat;
	$options['longitude'] = $long;
	$options['distance'] = $radius;

	if ($type) {
		$options['types'] = $type;
	}

	if ($subtype) {
		$options['subtypes'] = $subtype;
	}

	if ($owner_guid) {
		if (is_array($owner_guid)) {
			$options['owner_guids'] = $owner_guid;
		} else {
			$options['owner_guid'] = $owner_guid;
		}
	}

	$options['limit'] = $limit;

	$options['full_view'] = $fullview;
	$options['list_type_toggle'] = $listtypetoggle;
	$options['pagination'] = $pagination;

	return elgg_list_entities_from_location($options);
}

function list_entities_location($location, $type = "", $subtype = "", $owner_guid = 0, $limit = 10, $fullview = true, $listtypetoggle = false, $navigation = true) {
	elgg_deprecated_notice('list_entities_location() was deprecated. Use elgg_list_entities_from_metadata()', 1.8);

	return list_entities_from_metadata('location', $location, $type, $subtype, $owner_guid, $limit, $fullview, $listtypetoggle, $navigation);
}

function get_entities_in_area($lat, $long, $radius, $type = "", $subtype = "", $owner_guid = 0, $order_by = "", $limit = 10, $offset = 0, $count = false, $site_guid = 0, $container_guid = NULL) {
	elgg_deprecated_notice('get_entities_in_area() was deprecated by elgg_get_entities_from_location()!', 1.8);

	$options = array();

	$options['latitude'] = $lat;
	$options['longitude'] = $long;
	$options['distance'] = $radius;

	// set container_guid to owner_guid to emulate old functionality
	if ($owner_guid != "") {
		if (is_null($container_guid)) {
			$container_guid = $owner_guid;
		}
	}

	if ($type) {
		$options['types'] = $type;
	}

	if ($subtype) {
		$options['subtypes'] = $subtype;
	}

	if ($owner_guid) {
		if (is_array($owner_guid)) {
			$options['owner_guids'] = $owner_guid;
		} else {
			$options['owner_guid'] = $owner_guid;
		}
	}

	if ($container_guid) {
		if (is_array($container_guid)) {
			$options['container_guids'] = $container_guid;
		} else {
			$options['container_guid'] = $container_guid;
		}
	}

	$options['limit'] = $limit;

	if ($offset) {
		$options['offset'] = $offset;
	}

	if ($order_by) {
		$options['order_by'];
	}

	if ($site_guid) {
		$options['site_guid'];
	}

	if ($count) {
		$options['count'] = $count;
	}

	return elgg_get_entities_from_location($options);
}

function list_entities_from_metadata($meta_name, $meta_value = "", $entity_type = ELGG_ENTITIES_ANY_VALUE, $entity_subtype = ELGG_ENTITIES_ANY_VALUE, $owner_guid = 0, $limit = 10, $fullview = true, $listtypetoggle = true, $pagination = true, $case_sensitive = true) {

	elgg_deprecated_notice('list_entities_from_metadata() was deprecated by elgg_list_entities_from_metadata()!', 1.8);

	$offset = (int)get_input('offset');
	$limit = (int)$limit;
	$options = array('metadata_name' => $meta_name, 'metadata_value' => $meta_value,
		'types' => $entity_type, 'subtypes' => $entity_subtype, 'owner_guid' => $owner_guid,
		'limit' => $limit, 'offset' => $offset, 'count' => TRUE,
		'metadata_case_sensitive' => $case_sensitive);
	$count = elgg_get_entities_from_metadata($options);

	$options['count'] = FALSE;
	$entities = elgg_get_entities_from_metadata($options);

	return elgg_view_entity_list($entities, $count, $offset, $limit, $fullview, $listtypetoggle, $pagination);
}

function list_entities_from_metadata_multi($meta_array, $entity_type = "", $entity_subtype = "", $owner_guid = 0, $limit = 10, $fullview = true, $listtypetoggle = true, $pagination = true) {
	elgg_deprecated_notice(elgg_echo('deprecated:function', array(
		'list_entities_from_metadata_multi', 'elgg_get_entities_from_metadata')), 1.8);

	$offset = (int)get_input('offset');
	$limit = (int)$limit;
	$count = get_entities_from_metadata_multi($meta_array, $entity_type, $entity_subtype, $owner_guid, $limit, $offset, "", $site_guid, true);
	$entities = get_entities_from_metadata_multi($meta_array, $entity_type, $entity_subtype, $owner_guid, $limit, $offset, "", $site_guid, false);

	return elgg_view_entity_list($entities, $count, $offset, $limit, $fullview, $listtypetoggle, $pagination);
}

function add_submenu_item($label, $link, $group = 'default', $onclick = false, $selected = NULL) {
	elgg_deprecated_notice('add_submenu_item was deprecated by elgg_register_menu_item', 1.8);

	// submenu items were added in the page setup hook usually by checking
	// the context.  We'll pass in the current context here, which will
	// emulate that effect.
	// if context == 'main' (default) it probably means they always wanted
	// the menu item to show up everywhere.
	$context = elgg_get_context();

	if ($context == 'main') {
		$context = 'all';
	}

	$item = array('name' => $label, 'title' => $label, 'url' => $link, 'context' => $context,
		'section' => $group,);

	if ($selected) {
		$item['selected'] = true;
	}

	if ($onclick) {
		$js = "onclick=\"javascript:return confirm('" . elgg_echo('deleteconfirm') . "')\"";
		$item['vars'] = array('js' => $js);
	}

	return elgg_register_menu_item('page', $item);
}

function get_submenu() {
	elgg_deprecated_notice("get_submenu() has been deprecated by elgg_view_menu()", 1.8);
	return elgg_view_menu('owner_block', array('entity' => $owner,
		'class' => 'elgg-owner-block-menu',));
}

function add_menu($menu_name, $menu_url, $menu_children = array(), $context = "") {
	elgg_deprecated_notice('add_menu() deprecated by elgg_register_menu_item()', 1.8);

	return elgg_register_menu_item('site', array('name' => $menu_name, 'title' => $menu_name,
		'url' => $menu_url,));
}

function remove_menu($menu_name) {
	elgg_deprecated_notice("remove_menu() deprecated by elgg_unregister_menu_item()", 1.8);
	return elgg_unregister_menu_item('site', $menu_name);
}

function friendly_title($title) {
	elgg_deprecated_notice('friendly_title was deprecated by elgg_get_friendly_title', 1.8);
	return elgg_get_friendly_title($title);
}

function friendly_time($time) {
	elgg_deprecated_notice('friendly_time was deprecated by elgg_view_friendly_time', 1.8);
	return elgg_view_friendly_time($time);
}

function filter_string($string) {
	elgg_deprecated_notice('filter_string() was deprecated!', 1.8);

	// Convert it to lower and trim
	$string = strtolower($string);
	$string = trim($string);

	// Remove links and email addresses
	// match protocol://address/path/file.extension?some=variable&another=asf%
	$string = preg_replace("/\s([a-zA-Z]+:\/\/[a-z][a-z0-9\_\.\-]*[a-z]{2,6}" . "[a-zA-Z0-9\/\*\-\?\&\%\=]*)([\s|\.|\,])/iu", " ", $string);

	// match www.something.domain/path/file.extension?some=variable&another=asf%
	$string = preg_replace("/\s(www\.[a-z][a-z0-9\_\.\-]*[a-z]{2,6}" . "[a-zA-Z0-9\/\*\-\?\&\%\=]*)([\s|\.|\,])/iu", " ", $string);

	// match name@address
	$string = preg_replace("/\s([a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]" . "*\@[a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]{2,6})([\s|\.|\,])/iu", " ", $string);

	// Sanitise the string; remove unwanted characters
	$string = preg_replace('/\W/ui', ' ', $string);

	// Explode it into an array
	$terms = explode(' ', $string);

	// Remove any blacklist terms
	//$terms = array_filter($terms, 'remove_blacklist');

	return $terms;
}

function remove_blacklist($input) {
	elgg_deprecated_notice('remove_blacklist() was deprecated!', 1.8);

	global $CONFIG;

	if (!is_array($CONFIG->wordblacklist)) {
		return $input;
	}

	if (strlen($input) < 3 || in_array($input, $CONFIG->wordblacklist)) {
		return false;
	}

	return true;
}

function page_owner() {
	elgg_deprecated_notice('page_owner() was deprecated by elgg_get_page_owner_guid().', 1.8);
	return elgg_get_page_owner_guid();
}

function page_owner_entity() {
	elgg_deprecated_notice('page_owner_entity() was deprecated by elgg_get_page_owner_entity().', 1.8);
	return elgg_get_page_owner_entity();
}

function add_page_owner_handler($functionname) {
	elgg_deprecated_notice("add_page_owner_handler() was deprecated by the plugin hook 'page_owner', 'system'.", 1.8);
}

function set_page_owner($entitytoset = -1) {
	elgg_deprecated_notice('set_page_owner() was deprecated by elgg_set_page_owner_guid().', 1.8);
	elgg_set_page_owner_guid($entitytoset);
}

function set_context($context) {
	elgg_deprecated_notice('set_context() was deprecated by elgg_set_context().', 1.8);
	elgg_set_context($context);
	if (empty($context)) {
		return false;
	}
	return $context;
}

function get_context() {
	elgg_deprecated_notice('get_context() was deprecated by elgg_get_context().', 1.8);
	return elgg_get_context();

	// @todo - used to set context based on calling script
	// $context = get_plugin_name(true)
}

function get_installed_plugins($status = 'all') {
	global $CONFIG;

	elgg_deprecated_notice('get_installed_plugins() was deprecated by elgg_get_plugins()', 1.8);

	$plugins = elgg_get_plugins($status);

	if (!$plugins) {
		return array();
	}

	$installed_plugins = array();

	foreach ($plugins as $plugin) {
		if (!$plugin->isValid()) {
			continue;
		}

		$installed_plugins[$plugin->getID()] = array(
			'active' => $plugin->isActive(),
			'manifest' => $plugin->manifest->getManifest()
		);
	}

	return $installed_plugins;
}

function enable_plugin($plugin, $site_guid = null) {
	elgg_deprecated_notice('enable_plugin() was deprecated by ElggPlugin->activate()', 1.8);

	$plugin = sanitise_string($plugin);

	$site_guid = (int) $site_guid;
	if (!$site_guid) {
		$site = get_config('site');
		$site_guid = $site->guid;
	}

	try {
		$plugin = new ElggPlugin($plugin);
	} catch(Exception $e) {
		return false;
	}

	if (!$plugin->canActivate($site_guid)) {
		return false;
	}

	return $plugin->activate($site_guid);
}

function disable_plugin($plugin, $site_guid = 0) {
	elgg_deprecated_notice('disable_plugin() was deprecated by ElggPlugin->deactivate()', 1.8);

	$plugin = sanitise_string($plugin);

	$site_guid = (int) $site_guid;
	if (!$site_guid) {
		$site = get_config('site');
		$site_guid = $site->guid;
	}

	try {
		$plugin = new ElggPlugin($plugin);
	} catch(Exception $e) {
		return false;
	}

	return $plugin->deactivate($site_guid);
}

function is_plugin_enabled($plugin, $site_guid = 0) {
	elgg_deprecated_notice('is_plugin_enabled() was deprecated by ElggPlugin->isActive()', 1.8);

	$plugin = sanitise_string($plugin);

	$site_guid = (int) $site_guid;
	if (!$site_guid) {
		$site = get_config('site');
		$site_guid = $site->guid;
	}

	try {
		$plugin = new ElggPlugin($plugin);
	} catch(Exception $e) {
		return false;
	}

	return $plugin->isActive($site_guid);
}

function get_plugin_list() {
	elgg_deprecated_notice('get_plugin_list() is deprecated by elgg_get_plugin_ids_in_dir() or elgg_get_plugins()', 1.8);

	$plugins = elgg_get_plugins('any');

	$list = array();
	if ($plugins) {
		foreach ($plugins as $i => $plugin) {
			// in <=1.7 this returned indexed by multiples of 10.
			// uh...sure...why not.
			$index = ($i + 1) * 10;
			$list[$index] = $plugin->getID();
		}
	}

	return $list;
}

function regenerate_plugin_list($pluginorder = FALSE) {
	$msg = 'regenerate_plugin_list() is (sorta) deprecated by elgg_generate_plugin_entities() and'
			. ' elgg_set_plugin_priorities().';
	elgg_deprecated_notice($msg, 1.8);

	// they're probably trying to set it?
	if ($pluginorder) {
		if (elgg_generate_plugin_entities()) {
			// sort the plugins by the index numerically since we used
			// weird indexes in the old system.
			ksort($pluginorder, SORT_NUMERIC);
			return elgg_set_plugin_priorities($pluginorder);
		}
		return false;
	} else {
		// they're probably trying to regenerate from disk?
		return elgg_generate_plugin_entities();
	}
}

function get_plugin_name($mainfilename = false) {
	elgg_deprecated_notice('get_plugin_name() is deprecated by elgg_get_calling_plugin_id()', 1.8);

	return elgg_get_calling_plugin_id($mainfilename);
}

function load_plugin_manifest($plugin) {
	elgg_deprecated_notice('load_plugin_manifest() is deprecated by ElggPlugin->getManifest()', 1.8);

	$xml_file = elgg_get_plugin_path() . "$plugin/manifest.xml";

	try {
		$manifest = new ElggPluginManifest($xml_file, $plugin);
	} catch(Exception $e) {
		return false;
	}

	return $manifest->getManifest();
}

function check_plugin_compatibility($manifest_elgg_version_string) {
	elgg_deprecated_notice('check_plugin_compatibility() is deprecated by ElggPlugin->canActivate()', 1.8);

	$version = get_version();

	if (strpos($manifest_elgg_version_string, '.') === false) {
		// Using version
		$req_version = (int)$manifest_elgg_version_string;

		return ($version >= $req_version);
	}

	return false;
}

function find_plugin_settings($plugin_id = null) {
	elgg_deprecated_notice('find_plugin_setting() is deprecated by elgg_get_calling_plugin_entity() or elgg_get_plugin_from_id()', 1.8);
	if ($plugin_id) {
		return elgg_get_plugin_from_id($plugin_id);
	} else {
		return elgg_get_calling_plugin_entity();
	}
}

function get_entities_from_private_setting($name = "", $value = "", $type = "", $subtype = "",
$owner_guid = 0, $order_by = "", $limit = 10, $offset = 0, $count = false, $site_guid = 0,
$container_guid = null) {
	elgg_deprecated_notice('get_entities_from_private_setting() was deprecated by elgg_get_entities_from_private_setting()!', 1.8);

	$options = array();

	$options['private_setting_name'] = $name;
	$options['private_setting_value'] = $value;

	// set container_guid to owner_guid to emulate old functionality
	if ($owner_guid != "") {
		if (is_null($container_guid)) {
			$container_guid = $owner_guid;
		}
	}

	if ($type) {
		$options['types'] = $type;
	}

	if ($subtype) {
		$options['subtypes'] = $subtype;
	}

	if ($owner_guid) {
		if (is_array($owner_guid)) {
			$options['owner_guids'] = $owner_guid;
		} else {
			$options['owner_guid'] = $owner_guid;
		}
	}

	if ($container_guid) {
		if (is_array($container_guid)) {
			$options['container_guids'] = $container_guid;
		} else {
			$options['container_guid'] = $container_guid;
		}
	}

	$options['limit'] = $limit;

	if ($offset) {
		$options['offset'] = $offset;
	}

	if ($order_by) {
		$options['order_by'];
	}

	if ($site_guid) {
		$options['site_guid'];
	}

	if ($count) {
		$options['count'] = $count;
	}

	return elgg_get_entities_from_private_settings($options);
}

function get_entities_from_private_setting_multi(array $name, $type = "", $subtype = "",
$owner_guid = 0, $order_by = "", $limit = 10, $offset = 0, $count = false,
$site_guid = 0, $container_guid = null) {

	elgg_deprecated_notice('get_entities_from_private_setting_multi() was deprecated by elgg_get_entities_from_private_setting()!', 1.8);

	$options = array();

	$pairs = array();
	foreach ($name as $setting_name => $setting_value) {
		$pairs[] = array('name' => $setting_name, 'value' => $setting_value);
	}
	$options['private_setting_name_value_pairs'] = $pairs;

	// set container_guid to owner_guid to emulate old functionality
	if ($owner_guid != "") {
		if (is_null($container_guid)) {
			$container_guid = $owner_guid;
		}
	}

	if ($type) {
		$options['types'] = $type;
	}

	if ($subtype) {
		$options['subtypes'] = $subtype;
	}

	if ($owner_guid) {
		if (is_array($owner_guid)) {
			$options['owner_guids'] = $owner_guid;
		} else {
			$options['owner_guid'] = $owner_guid;
		}
	}

	if ($container_guid) {
		if (is_array($container_guid)) {
			$options['container_guids'] = $container_guid;
		} else {
			$options['container_guid'] = $container_guid;
		}
	}

	$options['limit'] = $limit;

	if ($offset) {
		$options['offset'] = $offset;
	}

	if ($order_by) {
		$options['order_by'];
	}

	if ($site_guid) {
		$options['site_guid'];
	}

	if ($count) {
		$options['count'] = $count;
	}

	return elgg_get_entities_from_private_settings($options);
}
// these were internal functions that perhaps can be removed rather than deprecated
function is_db_installed() {
	elgg_deprecated_notice('is_db_installed() has been deprecated', 1.8);
	return true;
}

function is_installed() {
	elgg_deprecated_notice('is_installed() has been deprecated', 1.8);
	return true;
}

/**#@-*/
