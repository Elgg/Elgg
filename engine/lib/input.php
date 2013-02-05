<?php
/**
 * Parameter input functions.
 * This file contains functions for getting input from get/post variables.
 *
 * @package Elgg.Core
 * @subpackage Input
 */

/**
 * Get some input from variables passed submitted through GET or POST.
 *
 * If using any data obtained from get_input() in a web page, please be aware that
 * it is a possible vector for a reflected XSS attack. If you are expecting an
 * integer, cast it to an int. If it is a string, escape quotes.
 *
 * Note: this function does not handle nested arrays (ex: form input of param[m][n])
 * because of the filtering done in htmlawed from the filter_tags call.
 * @todo Is this ^ still true?
 *
 * @param string $variable      The variable name we want.
 * @param mixed  $default       A default value for the variable if it is not found.
 * @param bool   $filter_result If true, then the result is filtered for bad tags.
 *
 * @return mixed
 */
function get_input($variable, $default = NULL, $filter_result = TRUE) {

	global $CONFIG;

	$result = $default;

	elgg_push_context('input');

	if (isset($CONFIG->input[$variable])) {
		$result = $CONFIG->input[$variable];

		if ($filter_result) {
			$result = filter_tags($result);
		}
	} elseif (isset($_REQUEST[$variable])) {
		if (is_array($_REQUEST[$variable])) {
			$result = $_REQUEST[$variable];
		} else {
			$result = trim($_REQUEST[$variable]);
		}

		if ($filter_result) {
			$result = filter_tags($result);
		}
	}

	elgg_pop_context();

	return $result;
}

/**
 * Sets an input value that may later be retrieved by get_input
 *
 * Note: this function does not handle nested arrays (ex: form input of param[m][n])
 *
 * @param string $variable The name of the variable
 * @param string $value    The value of the variable
 *
 * @return void
 */
function set_input($variable, $value) {
	global $CONFIG;
	if (!isset($CONFIG->input)) {
		$CONFIG->input = array();
	}

	if (is_array($value)) {
		array_walk_recursive($value, create_function('&$v, $k', '$v = trim($v);'));
		$CONFIG->input[trim($variable)] = $value;
	} else {
		$CONFIG->input[trim($variable)] = trim($value);
	}
}

/**
 * Filter tags from a given string based on registered hooks.
 *
 * @param mixed $var Anything that does not include an object (strings, ints, arrays)
 *					 This includes multi-dimensional arrays.
 *
 * @return mixed The filtered result - everything will be strings
 */
function filter_tags($var) {
	return elgg_trigger_plugin_hook('validate', 'input', null, $var);
}

/**
 * Validates an email address.
 *
 * @param string $address Email address.
 *
 * @return bool
 */
function is_email_address($address) {
	return filter_var($address, FILTER_VALIDATE_EMAIL) === $address;
}

/**
 * Load all the REQUEST variables into the sticky form cache
 *
 * Call this from an action when you want all your submitted variables
 * available if the submission fails validation and is sent back to the form
 *
 * @param string $form_name Name of the sticky form
 *
 * @return void
 * @link http://docs.elgg.org/Tutorials/UI/StickyForms
 * @since 1.8.0
 */
function elgg_make_sticky_form($form_name) {

	elgg_clear_sticky_form($form_name);

	if (!isset($_SESSION['sticky_forms'])) {
		$_SESSION['sticky_forms'] = array();
	}
	$_SESSION['sticky_forms'][$form_name] = array();

	foreach ($_REQUEST as $key => $var) {
		// will go through XSS filtering on the get function
		$_SESSION['sticky_forms'][$form_name][$key] = $var;
	}
}

/**
 * Clear the sticky form cache
 *
 * Call this if validation is successful in the action handler or
 * when they sticky values have been used to repopulate the form
 * after a validation error.
 *
 * @param string $form_name Form namespace
 *
 * @return void
 * @link http://docs.elgg.org/Tutorials/UI/StickyForms
 * @since 1.8.0
 */
function elgg_clear_sticky_form($form_name) {
	unset($_SESSION['sticky_forms'][$form_name]);
}

/**
 * Has this form been made sticky?
 *
 * @param string $form_name Form namespace
 *
 * @return boolean
 * @link http://docs.elgg.org/Tutorials/UI/StickyForms
 * @since 1.8.0
 */
function elgg_is_sticky_form($form_name) {
	return isset($_SESSION['sticky_forms'][$form_name]);
}

/**
 * Get a specific sticky variable
 *
 * @param string  $form_name     The name of the form
 * @param string  $variable      The name of the variable
 * @param mixed   $default       Default value if the variable does not exist in sticky cache
 * @param boolean $filter_result Filter for bad input if true
 *
 * @return mixed
 *
 * @todo should this filter the default value?
 * @link http://docs.elgg.org/Tutorials/UI/StickyForms
 * @since 1.8.0
 */
function elgg_get_sticky_value($form_name, $variable = '', $default = NULL, $filter_result = true) {
	if (isset($_SESSION['sticky_forms'][$form_name][$variable])) {
		$value = $_SESSION['sticky_forms'][$form_name][$variable];
		if ($filter_result) {
			// XSS filter result
			$value = filter_tags($value);
		}
		return $value;
	}
	return $default;
}

/**
 * Get all the values in a sticky form in an array
 *
 * @param string $form_name     The name of the form
 * @param bool   $filter_result Filter for bad input if true
 *
 * @return array
 * @since 1.8.0
 */
function elgg_get_sticky_values($form_name, $filter_result = true) {
	if (!isset($_SESSION['sticky_forms'][$form_name])) {
		return array();
	}

	$values = $_SESSION['sticky_forms'][$form_name];
	if ($filter_result) {
		foreach ($values as $key => $value) {
			// XSS filter result
			$values[$key] = filter_tags($value);
		}
	}
	return $values;
}

/**
 * Clear a specific sticky variable
 *
 * @param string $form_name The name of the form
 * @param string $variable  The name of the variable to clear
 *
 * @return void
 * @link http://docs.elgg.org/Tutorials/UI/StickyForms
 * @since 1.8.0
 */
function elgg_clear_sticky_value($form_name, $variable) {
	unset($_SESSION['sticky_forms'][$form_name][$variable]);
}

/**
 * Page handler for autocomplete endpoint.
 *
 * @todo split this into functions/objects, this is way too big
 *
 * /livesearch?q=<query>
 *
 * Other options include:
 *     match_on	   string all or array(groups|users|friends)
 *     match_owner int    0/1
 *     limit       int    default is 10
 *
 * @param array $page
 * @return string JSON string is returned and then exit
 * @access private
 */
function input_livesearch_page_handler($page) {
	global $CONFIG;

	// only return results to logged in users.
	if (!$user = elgg_get_logged_in_user_entity()) {
		exit;
	}

	if (!$q = get_input('term', get_input('q'))) {
		exit;
	}

	$q = sanitise_string($q);

	// replace mysql vars with escaped strings
	$q = str_replace(array('_', '%'), array('\_', '\%'), $q);

	$match_on = get_input('match_on', 'all');

	if (!is_array($match_on)) {
		$match_on = array($match_on);
	}

	// all = users and groups
	if (in_array('all', $match_on)) {
		$match_on = array('users', 'groups');
	}

	if (get_input('match_owner', false)) {
		$owner_where = 'AND e.owner_guid = ' . $user->getGUID();
	} else {
		$owner_where = '';
	}

	$limit = sanitise_int(get_input('limit', 10));

	// grab a list of entities and send them in json.
	$results = array();
	foreach ($match_on as $match_type) {
		switch ($match_type) {
			case 'users':
				$query = "SELECT * FROM {$CONFIG->dbprefix}users_entity as ue, {$CONFIG->dbprefix}entities as e
					WHERE e.guid = ue.guid
						AND e.enabled = 'yes'
						AND ue.banned = 'no'
						AND (ue.name LIKE '$q%' OR ue.name LIKE '% $q%' OR ue.username LIKE '$q%')
					LIMIT $limit
				";

				if ($entities = get_data($query)) {
					foreach ($entities as $entity) {
						// @todo use elgg_get_entities (don't query in a loop!)
						$entity = get_entity($entity->guid);
						/* @var ElggUser $entity */
						if (!$entity) {
							continue;
						}

						if (in_array('groups', $match_on)) {
							$value = $entity->guid;
						} else {
							$value = $entity->username;
						}

						$output = elgg_view_list_item($entity, array(
							'use_hover' => false,
							'class' => 'elgg-autocomplete-item',
						));

						$icon = elgg_view_entity_icon($entity, 'tiny', array(
							'use_hover' => false,
						));

						$result = array(
							'type' => 'user',
							'name' => $entity->name,
							'desc' => $entity->username,
							'guid' => $entity->guid,
							'label' => $output,
							'value' => $value,
							'icon' => $icon,
							'url' => $entity->getURL(),
						);
						$results[$entity->name . rand(1, 100)] = $result;
					}
				}
				break;

			case 'groups':
				// don't return results if groups aren't enabled.
				if (!elgg_is_active_plugin('groups')) {
					continue;
				}
				$query = "SELECT * FROM {$CONFIG->dbprefix}groups_entity as ge, {$CONFIG->dbprefix}entities as e
					WHERE e.guid = ge.guid
						AND e.enabled = 'yes'
						$owner_where
						AND (ge.name LIKE '$q%' OR ge.name LIKE '% $q%' OR ge.description LIKE '% $q%')
					LIMIT $limit
				";
				if ($entities = get_data($query)) {
					foreach ($entities as $entity) {
						// @todo use elgg_get_entities (don't query in a loop!)
						$entity = get_entity($entity->guid);
						/* @var ElggGroup $entity */
						if (!$entity) {
							continue;
						}

						$output = elgg_view_list_item($entity, array(
							'use_hover' => false,
							'class' => 'elgg-autocomplete-item',
						));

						$icon = elgg_view_entity_icon($entity, 'tiny', array(
							'use_hover' => false,
						));

						$result = array(
							'type' => 'group',
							'name' => $entity->name,
							'desc' => strip_tags($entity->description),
							'guid' => $entity->guid,
							'label' => $output,
							'value' => $entity->guid,
							'icon' => $icon,
							'url' => $entity->getURL(),
						);

						$results[$entity->name . rand(1, 100)] = $result;
					}
				}
				break;

			case 'friends':
				$query = "SELECT * FROM
						{$CONFIG->dbprefix}users_entity as ue,
						{$CONFIG->dbprefix}entity_relationships as er,
						{$CONFIG->dbprefix}entities as e
					WHERE er.relationship = 'friend'
						AND er.guid_one = {$user->getGUID()}
						AND er.guid_two = ue.guid
						AND e.guid = ue.guid
						AND e.enabled = 'yes'
						AND ue.banned = 'no'
						AND (ue.name LIKE '$q%' OR ue.name LIKE '% $q%' OR ue.username LIKE '$q%')
					LIMIT $limit
				";

				if ($entities = get_data($query)) {
					foreach ($entities as $entity) {
						// @todo use elgg_get_entities (don't query in a loop!)
						$entity = get_entity($entity->guid);
						/* @var ElggUser $entity */
						if (!$entity) {
							continue;
						}

						$output = elgg_view_list_item($entity, array(
							'use_hover' => false,
							'class' => 'elgg-autocomplete-item',
						));

						$icon = elgg_view_entity_icon($entity, 'tiny', array(
							'use_hover' => false,
						));

						$result = array(
							'type' => 'user',
							'name' => $entity->name,
							'desc' => $entity->username,
							'guid' => $entity->guid,
							'label' => $output,
							'value' => $entity->username,
							'icon' => $icon,
							'url' => $entity->getURL(),
						);
						$results[$entity->name . rand(1, 100)] = $result;
					}
				}
				break;

			default:
				header("HTTP/1.0 400 Bad Request", true);
				echo "livesearch: unknown match_on of $match_type";
				exit;
				break;
		}
	}

	ksort($results);
	header("Content-Type: application/json");
	echo json_encode(array_values($results));
	exit;
}

/**
 * Register input functions and sanitize input
 *
 * @return void
 * @access private
 */
function input_init() {
	// register an endpoint for live search / autocomplete.
	elgg_register_page_handler('livesearch', 'input_livesearch_page_handler');

	if (ini_get_bool('magic_quotes_gpc')) {

		/**
		 * do keys as well, cos array_map ignores them
		 *
		 * @param array $array Array of values
		 *
		 * @return array Sanitized array
		 */
		function stripslashes_arraykeys($array) {
			if (is_array($array)) {
				$array2 = array();
				foreach ($array as $key => $data) {
					if ($key != stripslashes($key)) {
						$array2[stripslashes($key)] = $data;
					} else {
						$array2[$key] = $data;
					}
				}
				return $array2;
			} else {
				return $array;
			}
		}

		/**
		 * Strip slashes on everything
		 *
		 * @param mixed $value The value to remove slashes from
		 *
		 * @return mixed
		 */
		function stripslashes_deep($value) {
			if (is_array($value)) {
				$value = stripslashes_arraykeys($value);
				$value = array_map('stripslashes_deep', $value);
			} else {
				$value = stripslashes($value);
			}
			return $value;
		}

		$_POST = stripslashes_arraykeys($_POST);
		$_GET = stripslashes_arraykeys($_GET);
		$_COOKIE = stripslashes_arraykeys($_COOKIE);
		$_REQUEST = stripslashes_arraykeys($_REQUEST);

		$_POST = array_map('stripslashes_deep', $_POST);
		$_GET = array_map('stripslashes_deep', $_GET);
		$_COOKIE = array_map('stripslashes_deep', $_COOKIE);
		$_REQUEST = array_map('stripslashes_deep', $_REQUEST);
		if (!empty($_SERVER['REQUEST_URI'])) {
			$_SERVER['REQUEST_URI'] = stripslashes($_SERVER['REQUEST_URI']);
		}
		if (!empty($_SERVER['QUERY_STRING'])) {
			$_SERVER['QUERY_STRING'] = stripslashes($_SERVER['QUERY_STRING']);
		}
		if (!empty($_SERVER['HTTP_REFERER'])) {
			$_SERVER['HTTP_REFERER'] = stripslashes($_SERVER['HTTP_REFERER']);
		}
		if (!empty($_SERVER['PATH_INFO'])) {
			$_SERVER['PATH_INFO'] = stripslashes($_SERVER['PATH_INFO']);
		}
		if (!empty($_SERVER['PHP_SELF'])) {
			$_SERVER['PHP_SELF'] = stripslashes($_SERVER['PHP_SELF']);
		}
		if (!empty($_SERVER['PATH_TRANSLATED'])) {
			$_SERVER['PATH_TRANSLATED'] = stripslashes($_SERVER['PATH_TRANSLATED']);
		}
	}
}

elgg_register_event_handler('init', 'system', 'input_init');
