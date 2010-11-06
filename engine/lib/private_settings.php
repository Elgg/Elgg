<?php
/**
 * Private settings for entities
 * Private settings provide metadata like storage of settings for plugins
 * and users.
 *
 * @package Elgg.Core
 * @subpackage PrivateSettings
 */

/**
 * Get entities based on their private data.
 *
 * @param string  $name           The name of the setting
 * @param string  $value          The value of the setting
 * @param string  $type           The type of entity (eg "user", "object" etc)
 * @param string  $subtype        The arbitrary subtype of the entity
 * @param int     $owner_guid     The GUID of the owning user
 * @param string  $order_by       The field to order by; by default, time_created desc
 * @param int     $limit          The number of entities to return; 10 by default
 * @param int     $offset         The indexing offset, 0 by default
 * @param boolean $count          Return a count of entities
 * @param int     $site_guid      The site to get entities for. 0 for current, -1 for any
 * @param mixed   $container_guid The container(s) GUIDs
 *
 * @return array A list of entities.
 * @todo deprecate
 */
function get_entities_from_private_setting($name = "", $value = "", $type = "", $subtype = "",
$owner_guid = 0, $order_by = "", $limit = 10, $offset = 0, $count = false, $site_guid = 0,
$container_guid = null) {

	global $CONFIG;

	if ($subtype === false || $subtype === null || $subtype === 0) {
		return false;
	}

	$name = sanitise_string($name);
	$value = sanitise_string($value);

	if ($order_by == "") {
		$order_by = "e.time_created desc";
	}
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
						if (!$subtypeval = (int) get_subtype_id($typekey, $subtypeval)) {
							return false;
						}
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
		if ($subtype AND !$subtype = get_subtype_id($type, $subtype)) {
			return false;
		}

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
		} else if (sizeof($owner_guid) > 0) {
			$owner_array = array_map('sanitise_int', $owner_guid);
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

	if ($name != "") {
		$where[] = "s.name = '$name'";
	}

	if ($value != "") {
		$where[] = "s.value='$value'";
	}

	if (!$count) {
		$query = "SELECT distinct e.*
			from {$CONFIG->dbprefix}entities e
			JOIN {$CONFIG->dbprefix}private_settings s ON e.guid=s.entity_guid where ";
	} else {
		$query = "SELECT count(distinct e.guid) as total
			from {$CONFIG->dbprefix}entities e JOIN {$CONFIG->dbprefix}private_settings s
			ON e.guid=s.entity_guid where ";
	}
	foreach ($where as $w) {
		$query .= " $w and ";
	}
	// Add access controls
	$query .= get_access_sql_suffix('e');
	if (!$count) {
		$query .= " order by $order_by";
		if ($limit) {
			// Add order and limit
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
 * Get entities based on their private data by multiple keys.
 *
 * @param string $name           The name of the setting
 * @param mixed  $type           Entity type
 * @param string $subtype        Entity subtype
 * @param int    $owner_guid     The GUID of the owning user
 * @param string $order_by       The field to order by; by default, time_created desc
 * @param int    $limit          The number of entities to return; 10 by default
 * @param int    $offset         The indexing offset, 0 by default
 * @param bool   $count          Count entities
 * @param int    $site_guid      Site GUID. 0 for current, -1 for any.
 * @param mixed  $container_guid Container GUID
 *
 * @return array A list of entities.
 * @todo deprecate
 */
function get_entities_from_private_setting_multi(array $name, $type = "", $subtype = "",
$owner_guid = 0, $order_by = "", $limit = 10, $offset = 0, $count = false,
$site_guid = 0, $container_guid = null) {

	global $CONFIG;

	if ($subtype === false || $subtype === null || $subtype === 0) {
		return false;
	}

	if ($order_by == "") {
		$order_by = "e.time_created desc";
	}
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
						if (!$subtypeval = (int) get_subtype_id($typekey, $subtypeval)) {
							return false;
						}
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
		if ($subtype AND !$subtype = get_subtype_id($type, $subtype)) {
			return false;
		}

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
		} else if (sizeof($owner_guid) > 0) {
			$owner_array = array_map('sanitise_int', $owner_guid);
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

	if ($name) {
		$s_join = "";
		$i = 1;
		foreach ($name as $k => $n) {
			$k = sanitise_string($k);
			$n = sanitise_string($n);
			$s_join .= " JOIN {$CONFIG->dbprefix}private_settings s$i ON e.guid=s$i.entity_guid";
			$where[] = "s$i.name = '$k'";
			$where[] = "s$i.value = '$n'";
			$i++;
		}
	}

	if (!$count) {
		$query = "SELECT distinct e.* from {$CONFIG->dbprefix}entities e $s_join where ";
	} else {
		$query = "SELECT count(distinct e.guid) as total
		from {$CONFIG->dbprefix}entities e $s_join where ";
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

/**
 * Gets a private setting for an entity.
 *
 * Plugin authors can set private data on entities.  By default
 * private data will not be searched or exported.
 *
 * @internal Private data is used to store settings for plugins
 * and user settings.
 *
 * @param int    $entity_guid The entity GUID
 * @param string $name        The name of the setting
 *
 * @return mixed The setting value, or false on failure
 * @see set_private_setting()
 * @see get_all_private_settings()
 * @see remove_private_setting()
 * @see remove_all_private_settings()
 * @link http://docs.elgg.org/DataModel/Entities/PrivateSettings
 */
function get_private_setting($entity_guid, $name) {
	global $CONFIG;
	$entity_guid = (int) $entity_guid;
	$name = sanitise_string($name);

	$query = "SELECT value from {$CONFIG->dbprefix}private_settings
		where name = '{$name}' and entity_guid = {$entity_guid}";
	$setting = get_data_row($query);

	if ($setting) {
		return $setting->value;
	}
	return false;
}

/**
 * Return an array of all private settings.
 *
 * @param int $entity_guid The entity GUID
 *
 * @return array|false
 * @see set_private_setting()
 * @see get_private_settings()
 * @see remove_private_setting()
 * @see remove_all_private_settings()
 * @link http://docs.elgg.org/DataModel/Entities/PrivateSettings
 */
function get_all_private_settings($entity_guid) {
	global $CONFIG;

	$entity_guid = (int) $entity_guid;

	$query = "SELECT * from {$CONFIG->dbprefix}private_settings where entity_guid = {$entity_guid}";
	$result = get_data($query);
	if ($result) {
		$return = array();
		foreach ($result as $r) {
			$return[$r->name] = $r->value;
		}

		return $return;
	}

	return false;
}

/**
 * Sets a private setting for an entity.
 *
 * @param int    $entity_guid The entity GUID
 * @param string $name        The name of the setting
 * @param string $value       The value of the setting
 *
 * @return mixed The setting ID, or false on failure
 * @see get_private_setting()
 * @see get_all_private_settings()
 * @see remove_private_setting()
 * @see remove_all_private_settings()
 * @link http://docs.elgg.org/DataModel/Entities/PrivateSettings
 */
function set_private_setting($entity_guid, $name, $value) {
	global $CONFIG;

	$entity_guid = (int) $entity_guid;
	$name = sanitise_string($name);
	$value = sanitise_string($value);

	$result = insert_data("INSERT into {$CONFIG->dbprefix}private_settings
		(entity_guid, name, value) VALUES
		($entity_guid, '{$name}', '{$value}')
		ON DUPLICATE KEY UPDATE value='$value'");
	if ($result === 0) {
		return true;
	}
	return $result;
}

/**
 * Deletes a private setting for an entity.
 *
 * @param int    $entity_guid The Entity GUID
 * @param string $name        The name of the setting
 *
 * @return true|false depending on success
 * @see get_private_setting()
 * @see get_all_private_settings()
 * @see set_private_setting()
 * @see remove_all_private_settings()
 * @link http://docs.elgg.org/DataModel/Entities/PrivateSettings
 */
function remove_private_setting($entity_guid, $name) {
	global $CONFIG;

	$entity_guid = (int) $entity_guid;
	$name = sanitise_string($name);

	return delete_data("DELETE from {$CONFIG->dbprefix}private_settings
		where name = '{$name}'
		and entity_guid = {$entity_guid}");
}

/**
 * Deletes all private settings for an entity.
 *
 * @param int $entity_guid The Entity GUID
 *
 * @return true|false depending on success
 * @see get_private_setting()
 * @see get_all_private_settings()
 * @see set_private_setting()
 * @see remove_private_settings()
 * @link http://docs.elgg.org/DataModel/Entities/PrivateSettings
 */
function remove_all_private_settings($entity_guid) {
	global $CONFIG;

	$entity_guid = (int) $entity_guid;
	return delete_data("DELETE from {$CONFIG->dbprefix}private_settings
		where entity_guid = {$entity_guid}");
}
