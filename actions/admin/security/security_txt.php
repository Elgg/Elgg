<?php
/**
 * Save the configuration of the security.txt contents
 */

$fields = [
	'contact' => true,
	'expires' => true,
	'encryption' => false,
	'acknowledgments' => false,
	'language' => false,
	'canonical' => false,
	'policy' => false,
	'hiring' => false,
	'csaf' => false,
];

// first validate all required inputs
foreach ($fields as $name => $required) {
	$value = get_input($name);
	if (!$required || !empty($value)) {
		continue;
	}
	
	return elgg_error_response(elgg_echo('error:missing_data'));
}

// save all data
foreach ($fields as $name => $required) {
	elgg_save_config("security_txt_{$name}", get_input($name) ?: null);
}

return elgg_ok_response('', elgg_echo('save:success'));
