<?php
/**
 * Elgg OpenDD import action.
 *
 * This action accepts data to import (in OpenDD format) and performs and import. It accepts
 * data as $data.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

// Safety
admin_gatekeeper();

// Get input
$data = get_input('data', '', false);

// Import
$return = import($data);

if ($return) {
	system_message(elgg_echo('importsuccess'));
} else {
	register_error(elgg_echo('importfail'));
}

forward($_SERVER['HTTP_REFERER']);
