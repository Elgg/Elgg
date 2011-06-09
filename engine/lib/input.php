<?php
/**
 * Parameter input functions.
 * This file contains functions for getting input from get/post variables.
 *
 * @package Elgg
 * @subpackage Core
 */

/**
 * Get some input from variables passed on the GET or POST line.
 * 
 * If using any data obtained from get_input() in a page, please be aware that
 * it is a possible vector for a reflected XSS attack. If you are expecting an
 * integer, cast it to an int. If it is a string, escape quotes.
 *
 * Note: this function does not handle nested arrays (ex: form input of param[m][n])
 * because of the filtering done in htmlawed from the filter_tags call.
 * @todo Is this ^ still?
 *
 * @param $variable string The variable we want to return.
 * @param $default mixed A default value for the variable if it is not found.
 * @param $filter_result If true then the result is filtered for bad tags.
 */
function get_input($variable, $default = "", $filter_result = true) {

	global $CONFIG;

	if (isset($CONFIG->input[$variable])) {
		$var = $CONFIG->input[$variable];

		if ($filter_result) {
			$var = filter_tags($var);
		}

		return $var;
	}

	if (isset($_REQUEST[$variable])) {
		if (is_array($_REQUEST[$variable])) {
			$var = $_REQUEST[$variable];
		} else {
			$var = trim($_REQUEST[$variable]);
		}

		if ($filter_result) {
			$var = filter_tags($var);
		}

		return $var;
	}

	return $default;
}

/**
 * Sets an input value that may later be retrieved by get_input
 *
 * Note: this function does not handle nested arrays (ex: form input of param[m][n])
 *
 * @param string $variable The name of the variable
 * @param string $value The value of the variable
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
 *					This includes multi-dimensional arrays.
 * @return mixed The filtered result - everything will be strings
 */
function filter_tags($var) {
	return trigger_plugin_hook('validate', 'input', null, $var);
}

/**
 * Sanitise file paths for input, ensuring that they begin and end with slashes etc.
 *
 * @param string $path The path
 * @return string
 */
function sanitise_filepath($path) {
	// Convert to correct UNIX paths
	$path = str_replace('\\', '/', $path);

	// Sort trailing slash
	$path = trim($path);
	// rtrim defaults plus /
	$path = rtrim($path, " \n\t\0\x0B/");
	$path = $path . "/";

	return $path;
}

/**
 * Page handler for autocomplete endpoint.
 *
 * @param $page
 * @return unknown_type
 */
function input_livesearch_page_handler($page) {
	global $CONFIG;
	// only return results to logged in users.
	if (!$user = get_loggedin_user()) {
		exit;
	}

	if (!$q = get_input('q')) {
		exit;
	}

	$q = sanitise_string($q);

	// replace mysql vars with escaped strings
	$q = str_replace(array('_', '%'), array('\_', '\%'), $q);

	$match_on = get_input('match_on', 'all');
	if ($match_on == 'all' || $match_on[0] == 'all') {
		$match_on = array('users', 'groups');
	}

	if (!is_array($match_on)) {
		$match_on = array($match_on);
	}

	if (get_input('match_owner', false)) {
		$owner_guid = $user->getGUID();
		$owner_where = 'AND e.owner_guid = ' . $user->getGUID();
	} else {
		$owner_guid = null;
		$owner_where = '';
	}

	$limit = get_input('limit', 10);

	// grab a list of entities and send them in json.
	$results = array();
	foreach ($match_on as $type) {
		switch ($type) {
			case 'all':
				// only need to pull up title from objects.

				if (!$entities = elgg_get_entities(array('owner_guid' => $owner_guid, 'limit' => $limit)) AND is_array($entities)) {
					$results = array_merge($results, $entities);
				}
				break;

			case 'users':
				$query = "SELECT * FROM {$CONFIG->dbprefix}users_entity as ue, {$CONFIG->dbprefix}entities as e
					WHERE e.guid = ue.guid
						AND e.enabled = 'yes'
						AND ue.banned = 'no'
						AND (ue.name LIKE '$q%' OR ue.username LIKE '$q%')
					LIMIT $limit
				";

				if ($entities = get_data($query)) {
					foreach ($entities as $entity) {
						$json = json_encode(array(
							'type' => 'user',
							'name' => $entity->name,
							'desc' => $entity->username,
							'icon' => '<img class="livesearch_icon" src="' . get_entity($entity->guid)->getIcon('tiny') . '" />',
							'guid' => $entity->guid
						));
						$results[$entity->name . rand(1,100)] = $json;
					}
				}
				break;

			case 'groups':
				// don't return results if groups aren't enabled.
				if (!is_plugin_enabled('groups')) {
					continue;
				}
				$query = "SELECT * FROM {$CONFIG->dbprefix}groups_entity as ge, {$CONFIG->dbprefix}entities as e
					WHERE e.guid = ge.guid
						AND e.enabled = 'yes'
						$owner_where
						AND (ge.name LIKE '$q%' OR ge.description LIKE '%$q%')
					LIMIT $limit
				";
				if ($entities = get_data($query)) {
					foreach ($entities as $entity) {
						$json = json_encode(array(
							'type' => 'group',
							'name' => $entity->name,
							'desc' => strip_tags($entity->description),
							'icon' => '<img class="livesearch_icon" src="' . get_entity($entity->guid)->getIcon('tiny') . '" />',
							'guid' => $entity->guid
						));
						//$results[$entity->name . rand(1,100)] = "$json|{$entity->guid}";
						$results[$entity->name . rand(1,100)] = $json;
					}
				}
				break;

			case 'friends':
				$access = get_access_sql_suffix();
				$query = "SELECT * FROM {$CONFIG->dbprefix}users_entity as ue, {$CONFIG->dbprefix}entity_relationships as er, {$CONFIG->dbprefix}entities as e
					WHERE er.relationship = 'friend'
						AND er.guid_one = {$user->getGUID()}
						AND er.guid_two = ue.guid
						AND e.guid = ue.guid
						AND e.enabled = 'yes'
						AND ue.banned = 'no'
						AND (ue.name LIKE '$q%' OR ue.username LIKE '$q%')
					LIMIT $limit
				";

				if ($entities = get_data($query)) {
					foreach ($entities as $entity) {
						$json = json_encode(array(
							'type' => 'user',
							'name' => $entity->name,
							'desc' => $entity->username,
							'icon' => '<img class="livesearch_icon" src="' . get_entity($entity->guid)->getIcon('tiny') . '" />',
							'guid' => $entity->guid
						));
						$results[$entity->name . rand(1,100)] = $json;
					}
				}
				break;

			default:
				// arbitrary subtype.
				//@todo you cannot specify a subtype without a type. 
				// did this ever work?
				elgg_get_entities(array('subtype' => $type, 'owner_guid' => $owner_guid));
				break;
		}
	}

	ksort($results);
	echo implode($results, "\n");
	exit;
}


function input_init() {
	// register an endpoint for live search / autocomplete.
	register_page_handler('livesearch', 'input_livesearch_page_handler');

	if (ini_get_bool('magic_quotes_gpc') ) {
		//do keys as well, cos array_map ignores them
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

register_elgg_event_handler('init','system','input_init');
