<?php
/**
 * Page handler for autocomplete endpoint
 *
 * /livesearch/<match_on>?q=<query>
 */

use Elgg\Exceptions\Http\PageNotFoundException;

/* @var $request \Elgg\Http\Request */
$request = elgg_extract('request', $vars);

// pass all input params (GET & POST) into $vars
$input_params = $request->getParams();
$ignored_inputs = ['view', '_route'];
foreach ($input_params as $name => $value) {
	if (in_array($name, $ignored_inputs)) {
		continue;
	}
	
	// request can contain wrong value
	$value = get_input($name, $value);
	
	// set request param in $vars, but don't overrule already set values
	// extract with false in order to replace empty value in $vars with request data
	$vars[$name] = elgg_extract($name, $vars, $value, false);
}

$match_on = elgg_extract('match_on', $vars);

// livesearch will result in a json response
elgg_set_viewtype('json');

if (!elgg_view_exists("resources/livesearch/$match_on")) {
	throw new PageNotFoundException();
}

echo elgg_view("resources/livesearch/$match_on", $vars);
