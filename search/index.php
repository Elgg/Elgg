<?php
/**
 * Redirect to the new search page
 *
 * Needed for legacy themes.
 */

require_once(dirname(dirname(__FILE__)) . "/engine/start.php");

$params = array(
	'search_type',
	'q',
	'tag',
	'limit',
	'offset',
	'entity_type',
	'entity_subtype',
	'owner_guid',
	'friends'
);

// determine all passed parameters
$vars = array();
foreach ($params as $var) {
	if ($value = get_input($var, FALSE)) {
		$vars[$var] = $value;
	}
}

// generate a new GET query URI
$query = http_build_query($vars);
$url = "{$CONFIG->wwwroot}pg/search/?$query";

// send to proper search page
forward($url);
