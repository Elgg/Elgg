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
function get_input($variable, $default = null, $filter_result = true) {
	return _elgg_services()->input->get($variable, $default, $filter_result);
}

/**
 * Sets an input value that may later be retrieved by get_input
 *
 * Note: this function does not handle nested arrays (ex: form input of param[m][n])
 *
 * @param string          $variable The name of the variable
 * @param string|string[] $value    The value of the variable
 *
 * @return void
 */
function set_input($variable, $value) {
	_elgg_services()->input->set($variable, $value);
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
 * Returns the current page's complete URL.
 * 
 * It uses the configured site URL for the hostname rather than depending on
 * what the server uses to populate $_SERVER.
 *
 * @return string The current page URL.
 */
function current_page_url() {
	$url = parse_url(elgg_get_site_url());

	$page = $url['scheme'] . "://" . $url['host'];

	if (isset($url['port']) && $url['port']) {
		$page .= ":" . $url['port'];
	}

	$page = trim($page, "/");

	$page .= _elgg_services()->request->getRequestUri();

	return $page;
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
 * Load all the GET and POST variables into the sticky form cache
 *
 * Call this from an action when you want all your submitted variables
 * available if the submission fails validation and is sent back to the form
 *
 * @param string $form_name Name of the sticky form
 *
 * @return void
 * @since 1.8.0
 */
function elgg_make_sticky_form($form_name) {
	_elgg_services()->stickyForms->makeStickyForm($form_name);
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
 * @since 1.8.0
 */
function elgg_clear_sticky_form($form_name) {
	_elgg_services()->stickyForms->clearStickyForm($form_name);
}

/**
 * Has this form been made sticky?
 *
 * @param string $form_name Form namespace
 *
 * @return boolean
 * @since 1.8.0
 */
function elgg_is_sticky_form($form_name) {
	return _elgg_services()->stickyForms->isStickyForm($form_name);
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
 * @since 1.8.0
 */
function elgg_get_sticky_value($form_name, $variable = '', $default = null, $filter_result = true) {
	return _elgg_services()->stickyForms->getStickyValue($form_name, $variable, $default, $filter_result);

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
	return _elgg_services()->stickyForms->getStickyValues($form_name, $filter_result);
}

/**
 * Clear a specific sticky variable
 *
 * @param string $form_name The name of the form
 * @param string $variable  The name of the variable to clear
 *
 * @return void
 * @since 1.8.0
 */
function elgg_clear_sticky_value($form_name, $variable) {
	_elgg_services()->stickyForms->clearStickyValue($form_name, $variable);
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
 *     name        string default "members"
 *
 * @param array $page
 * @return string JSON string is returned and then exit
 * @access private
 */
function input_livesearch_page_handler($page) {
	$dbprefix = elgg_get_config('dbprefix');

	// only return results to logged in users.
	if (!$user = elgg_get_logged_in_user_entity()) {
		exit;
	}

	if (!$q = get_input('term', get_input('q'))) {
		exit;
	}

	$input_name = get_input('name', 'members');

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

	$owner_guid = ELGG_ENTITIES_ANY_VALUE;
	if (get_input('match_owner', false)) {
		$owner_guid = $user->getGUID();
	}

	$limit = sanitise_int(get_input('limit', elgg_get_config('default_limit')));

	// grab a list of entities and send them in json.
	$results = array();
	foreach ($match_on as $match_type) {
		switch ($match_type) {
			case 'users':
				$options = array(
					'type' => 'user',
					'limit' => $limit,
					'joins' => array("JOIN {$dbprefix}users_entity ue ON e.guid = ue.guid"),
					'wheres' => array(
						"ue.banned = 'no'",
						"(ue.name LIKE '$q%' OR ue.name LIKE '% $q%' OR ue.username LIKE '$q%')"
					)
				);
				
				$entities = elgg_get_entities($options);
				if (!empty($entities)) {
					foreach ($entities as $entity) {
						
						if (in_array('groups', $match_on)) {
							$value = $entity->guid;
						} else {
							$value = $entity->username;
						}

						$output = elgg_view_list_item($entity, array(
							'use_hover' => false,
							'use_link' => false,
							'class' => 'elgg-autocomplete-item',
							'title' => $entity->name, // Default title would be a link
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
							'html' => elgg_view('input/userpicker/item', array(
								'entity' => $entity,
								'input_name' => $input_name,
							)),
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
				
				$options = array(
					'type' => 'group',
					'limit' => $limit,
					'owner_guid' => $owner_guid,
					'joins' => array("JOIN {$dbprefix}groups_entity ge ON e.guid = ge.guid"),
					'wheres' => array(
						"(ge.name LIKE '$q%' OR ge.name LIKE '% $q%' OR ge.description LIKE '% $q%')"
					)
				);
				
				$entities = elgg_get_entities($options);
				if (!empty($entities)) {
					foreach ($entities as $entity) {
						$output = elgg_view_list_item($entity, array(
							'use_hover' => false,
							'class' => 'elgg-autocomplete-item',
							'full_view' => false,
							'href' => false,
							'title' => $entity->name, // Default title would be a link
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
				$options = array(
					'type' => 'user',
					'limit' => $limit,
					'relationship' => 'friend',
					'relationship_guid' => $user->getGUID(),
					'joins' => array("JOIN {$dbprefix}users_entity ue ON e.guid = ue.guid"),
					'wheres' => array(
						"ue.banned = 'no'",
						"(ue.name LIKE '$q%' OR ue.name LIKE '% $q%' OR ue.username LIKE '$q%')"
					)
				);
				
				$entities = elgg_get_entities_from_relationship($options);
				if (!empty($entities)) {
					foreach ($entities as $entity) {
						
						$output = elgg_view_list_item($entity, array(
							'use_hover' => false,
							'use_link' => false,
							'class' => 'elgg-autocomplete-item',
							'title' => $entity->name, // Default title would be a link
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
							'html' => elgg_view('input/userpicker/item', array(
								'entity' => $entity,
								'input_name' => $input_name,
							)),
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
 * Strip slashes from array keys
 *
 * @param array $array Array of values
 *
 * @return array Sanitized array
 * @access private
 */
function _elgg_stripslashes_arraykeys($array) {
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
 * Strip slashes
 *
 * @param mixed $value The value to remove slashes from
 *
 * @return mixed
 * @access private
 */
function _elgg_stripslashes_deep($value) {
	if (is_array($value)) {
		$value = _elgg_stripslashes_arraykeys($value);
		$value = array_map('_elgg_stripslashes_deep', $value);
	} else {
		$value = stripslashes($value);
	}
	return $value;
}

/**
 * Initialize the input library
 *
 * @return void
 * @access private
 */
function _elgg_input_init() {
	// register an endpoint for live search / autocomplete.
	elgg_register_page_handler('livesearch', 'input_livesearch_page_handler');

	// backward compatible for plugins directly accessing globals
	if (get_magic_quotes_gpc()) {
		$_POST = array_map('_elgg_stripslashes_deep', $_POST);
		$_GET = array_map('_elgg_stripslashes_deep', $_GET);
		$_COOKIE = array_map('_elgg_stripslashes_deep', $_COOKIE);
		$_REQUEST = array_map('_elgg_stripslashes_deep', $_REQUEST);
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

return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_input_init');
};
