<?php
/**
 * Helper functions for ECML.
 *
 * @package ECML
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.org/
 */


/**
 * Parse ECML keywords
 *
 * @param array $matches
 * @return string html
 */
function ecml_parse_view_match($matches) {
	global $CONFIG;

	$view = $CONFIG->ecml_current_view;

	$keyword = trim($matches[1]);
	$params_string = trim($matches[2]);

	// reject keyword if blacklisted for view or invalid
	if (!ecml_is_valid_keyword($keyword, $view)) {
		return $matches[0];
	}

	switch ($keyword) {
		case 'entity_list':
			$options = ecml_keywords_parse_entity_params($params_string);
			// must use this lower-level function because I missed refactoring
			// the list entity functions for relationships.
			// (which, since you're here, is the only function that runs through all
			// possible options for elgg_get_entities*() functions...)
			$entities = elgg_get_entities_from_relationship($options);
			$content = elgg_view_entity_list($entities, count($entities), $options['offset'],
				$options['limit'], $options['full_view'], $options['view_type_toggle'], $options['pagination']);
			break;

		case 'view':
			// src is a required attribute of view
			$vars = ecml_keywords_tokenize_params($params_string);
			$vars['ecml_keyword'] = $keyword;
			$vars['ecml_params_string'] = $params_string;
			$content = elgg_view($vars['src'], $vars);

			break;

		default:
			// match against custom keywords with optional args
			$keyword_info = $CONFIG->ecml_keywords[$keyword];
			$vars = ecml_keywords_tokenize_params($params_string);
			$vars['ecml_keyword'] = $keyword;
			$vars['ecml_params_string'] = $params_string;
			$content = elgg_view($keyword_info['view'], $vars);
			break;
	}

	// if nothing matched return the original string.
	// @todo this might be undesirable.  will show ugly code everywhere
	// if you delete a file or something.
	if (!$content) {
		$content = $matches[0];
	}

	return $content;
}

/**
 * Creates an array from a name=value name2='value2' name3="value3" string.
 *
 * @param $string
 * @return array
 */
function ecml_keywords_tokenize_params($string) {

	if (empty($string)) {
		return array();
	}

	$params = array();
	$pos = 0;
	$char = substr($string, $pos, 1);

	// working var for assembling name and values
	$operand = $name = '';

	while ($char !== FALSE) {
		switch ($char) {
			// handle quoted names/values
			case '"':
			case "'":
				$quote = $char;

				$next_char = substr($string, ++$pos, 1);
				while ($next_char != $quote) {
					if ($next_char === FALSE) {
						// no matching quote. bail.
						return array();
					}
					$operand .= $next_char;
					$next_char = substr($string, ++$pos, 1);
				}
				break;

			case ECML_ATTR_SEPARATOR:
				// normalize true and false
				if ($operand == 'true'){
					$operand = TRUE;
				} elseif ($operand == 'false') {
					$operand = FALSE;
				}
				$params[$name] = $operand;
				$operand = $name = '';
				break;

			case ECML_ATTR_OPERATOR:
				// save name, switch to value
				$name = $operand;
				$operand = '';
				break;

			default:
				$operand .= $char;
		}

		$char = substr($string, ++$pos, 1);
	}

	// need to get the last attr
	if ($name && $operand) {
		if ($operand == 'true'){
			$operand = TRUE;
		} elseif ($operand == 'false') {
			$operand = FALSE;
		}
		$params[$name] = $operand;
	}

	return $params;

	// this is much faster, but doesn't allow quoting.
//	$pairs = array_map('trim', explode(',', $string));
//	$params = array();
//
//	foreach ($pairs as $pair) {
//		list($name, $value) = explode('=', $pair);
//
//		$name = trim($name);
//		$value = trim($value);
//
//		// normalize BOOL values
//		if ($value === 'true') {
//			$value = TRUE;
//		} elseif ($value === 'false') {
//			$value = FALSE;
//		}
//
//		// don't check against value since a falsy/empty value is valid.
//		if ($name) {
//			$params[$name] = $value;
//		}
//	}
//
//	return $params;
}

/**
 * Returns an options array suitable for using in elgg_get_entities()
 *
 * @param string $string "name=value, name2=value2"
 * @return array
 */
function ecml_keywords_parse_entity_params($string) {
	$params = ecml_keywords_tokenize_params($string);

	// handle some special cases
	if (isset($params['owner'])) {
		if ($user = get_user_by_username($params['owner'])) {
			$params['owner_guid'] = $user->getGUID();
		}
	}

	// @todo probably need to add more for
	// group -> container_guid, etc
	return $params;
}

/**
 * Checks granular permissions if keyword is valid for view
 *
 * @param unknown_type $keyword
 * @param unknown_type $view
 * @return bool
 */
function ecml_is_valid_keyword($keyword, $view = NULL) {
	global $CONFIG;

	// this isn't even a real keyword.
	if (!isset($CONFIG->ecml_keywords[$keyword])) {
		return FALSE;
	}

	// this keyword is restricted to certain views
	if (isset($CONFIG->ecml_keywords[$keyword]['restricted'])
	&& !in_array($view, $CONFIG->ecml_keywords[$keyword]['restricted'])) {
		return FALSE;
	}

	$views = $CONFIG->ecml_permissions;

	// this is a blacklist, so return TRUE by default.
	$r = TRUE;

	if (isset($views[$view]) && in_array($keyword, $views[$view])) {
		$r = FALSE;
	}

	return $r;
}
