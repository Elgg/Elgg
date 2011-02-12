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
 * Returns entities based upon private settings.  Also accepts all
 * options available to elgg_get_entities().  Supports
 * the singular option shortcut.
 *
 * @see elgg_get_entities
 *
 * @param array $options Array in format:
 *
 * 	private_setting_names => NULL|ARR private setting names
 *
 * 	private_setting_values => NULL|ARR metadata values
 *
 * 	private_setting_name_value_pairs => NULL|ARR (
 *                                         name => 'name',
 *                                         value => 'value',
 *                                         'operand' => '=',
 *                                        )
 * 	                             Currently if multiple values are sent via
 *                               an array (value => array('value1', 'value2')
 *                               the pair's operand will be forced to "IN".
 *
 * 	private_setting_name_value_pairs_operator => NULL|STR The operator to use for combining
 *                                        (name = value) OPERATOR (name = value); default AND
 *
 *  private_setting_name_prefix => STR A prefix to apply to all private settings. Used to
 *                                     namespace plugin user settings or by plugins to namespace
 *                                     their own settings.
 *
 *
 * @return array
 * @since 1.8.0
 */
function elgg_get_entities_from_private_settings(array $options = array()) {
	$defaults = array(
		'private_setting_names'                     =>	ELGG_ENTITIES_ANY_VALUE,
		'private_setting_values'                    =>	ELGG_ENTITIES_ANY_VALUE,
		'private_setting_name_value_pairs'          =>	ELGG_ENTITIES_ANY_VALUE,
		'private_setting_name_value_pairs_operator' => 'AND',
		'private_setting_name_prefix'				=> '',
	);

	$options = array_merge($defaults, $options);

	$singulars = array('private_setting_name', 'private_setting_value',
		'private_setting_name_value_pair');

	$options = elgg_normalise_plural_options_array($options, $singulars);

	$clauses = elgg_get_entity_private_settings_where_sql('e', $options['private_setting_names'],
		$options['private_setting_values'], $options['private_setting_name_value_pairs'],
		$options['private_setting_name_value_pairs_operator'], $options['private_setting_name_prefix']);

	if ($clauses) {
		// merge wheres to pass to get_entities()
		if (isset($options['wheres']) && !is_array($options['wheres'])) {
			$options['wheres'] = array($options['wheres']);
		} elseif (!isset($options['wheres'])) {
			$options['wheres'] = array();
		}

		$options['wheres'] = array_merge($options['wheres'], $clauses['wheres']);

		// merge joins to pass to get_entities()
		if (isset($options['joins']) && !is_array($options['joins'])) {
			$options['joins'] = array($options['joins']);
		} elseif (!isset($options['joins'])) {
			$options['joins'] = array();
		}

		$options['joins'] = array_merge($options['joins'], $clauses['joins']);
	}

	return elgg_get_entities($options);
}

/**
 * Returns private setting name and value SQL where/join clauses for entities.
 *
 * @param string     $table         Entities table name
 * @param array|null $names         Array of names
 * @param array|null $values        Array of values
 * @param array|null $pairs         Array of names / values / operands
 * @param string     $pair_operator Operator for joining pairs where clauses
 * @param string     $name_prefix   A string to prefix all names with
 * @return array
 * @since 1.8.0
 */
function elgg_get_entity_private_settings_where_sql($table, $names = NULL, $values = NULL,
$pairs = NULL, $pair_operator = 'AND', $name_prefix = '') {

	global $CONFIG;

	// @todo short circuit test

	$return = array (
		'joins' => array (),
		'wheres' => array(),
	);

	$return['joins'][] = "JOIN {$CONFIG->dbprefix}private_settings ps on
		{$table}.guid = ps.entity_guid";

	$wheres = array();

	// get names wheres
	$names_where = '';
	if ($names !== NULL) {
		if (!is_array($names)) {
			$names = array($names);
		}

		$sanitised_names = array();
		foreach ($names as $name) {
			$name = $name_prefix . $name;
			$sanitised_names[] = '\'' . sanitise_string($name) . '\'';
		}

		$names_str = implode(',', $sanitised_names);
		if ($names_str) {
			$names_where = "(ps.name IN ($names_str))";
		}
	}

	// get values wheres
	$values_where = '';
	if ($values !== NULL) {
		if (!is_array($values)) {
			$values = array($values);
		}

		$sanitised_values = array();
		foreach ($values as $value) {
			// normalize to 0
			if (!$value) {
				$value = 0;
			}
			$sanitised_values[] = '\'' . sanitise_string($value) . '\'';
		}

		$values_str = implode(',', $sanitised_values);
		if ($values_str) {
			$values_where = "(ps.value IN ($values_str))";
		}
	}

	if ($names_where && $values_where) {
		$wheres[] = "($names_where AND $values_where)";
	} elseif ($names_where) {
		$wheres[] = "($names_where)";
	} elseif ($values_where) {
		$wheres[] = "($values_where)";
	}

	// add pairs which must be in arrays.
	if (is_array($pairs)) {
		// join counter for incremental joins in pairs
		$i = 1;

		// check if this is an array of pairs or just a single pair.
		if (isset($pairs['name']) || isset($pairs['value'])) {
			$pairs = array($pairs);
		}

		$pair_wheres = array();

		foreach ($pairs as $index => $pair) {
			// @todo move this elsewhere?
			// support shortcut 'n' => 'v' method.
			if (!is_array($pair)) {
				$pair = array(
					'name' => $index,
					'value' => $pair
				);
			}

			// must have at least a name and value
			if (!isset($pair['name']) || !isset($pair['value'])) {
				// @todo should probably return false.
				continue;
			}

			if (isset($pair['operand'])) {
				$operand = sanitise_string($pair['operand']);
			} else {
				$operand = ' = ';
			}

			// for comparing
			$trimmed_operand = trim(strtolower($operand));

			// if the value is an int, don't quote it because str '15' < str '5'
			// if the operand is IN don't quote it because quoting should be done already.
			if (is_numeric($pair['value'])) {
				$value = sanitise_string($pair['value']);
			} else if (is_array($pair['value'])) {
				$values_array = array();

				foreach ($pair['value'] as $pair_value) {
					if (is_numeric($pair_value)) {
						$values_array[] = sanitise_string($pair_value);
					} else {
						$values_array[] = "'" . sanitise_string($pair_value) . "'";
					}
				}

				if ($values_array) {
					$value = '(' . implode(', ', $values_array) . ')';
				}

				// @todo allow support for non IN operands with array of values.
				// will have to do more silly joins.
				$operand = 'IN';
			} else if ($trimmed_operand == 'in') {
				$value = "({$pair['value']})";
			} else {
				$value = "'" . sanitise_string($pair['value']) . "'";
			}

			$name = sanitise_string($name_prefix . $pair['name']);

			// @todo The multiple joins are only needed when the operator is AND
			$return['joins'][] = "JOIN {$CONFIG->dbprefix}private_settings ps{$i}
				on {$table}.guid = ps{$i}.entity_guid";

			$pair_wheres[] = "(ps{$i}.name = '$name' AND ps{$i}.value
				$operand $value)";

			$i++;
		}

		$where = implode (" $pair_operator ", $pair_wheres);
		if ($where) {
			$wheres[] = "($where)";
		}
	}

	$where = implode(' AND ', $wheres);
	if ($where) {
		$return['wheres'][] = "($where)";
	}

	return $return;
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

	$entity = get_entity($entity_guid);
	if (!$entity instanceof ElggEntity) {
		return false;
	}

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
	$entity = get_entity($entity_guid);
	if (!$entity instanceof ElggEntity) {
		return false;
	}

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

	$entity = get_entity($entity_guid);
	if (!$entity instanceof ElggEntity) {
		return false;
	}

	$result = insert_data("INSERT into {$CONFIG->dbprefix}private_settings
		(entity_guid, name, value) VALUES
		($entity_guid, '$name', '$value')
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

	$entity = get_entity($entity_guid);
	if (!$entity instanceof ElggEntity) {
		return false;
	}

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

	$entity = get_entity($entity_guid);
	if (!$entity instanceof ElggEntity) {
		return false;
	}

	return delete_data("DELETE from {$CONFIG->dbprefix}private_settings
		where entity_guid = {$entity_guid}");
}
