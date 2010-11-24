<?php
/**
 * Elgg OpenDD import action.
 *
 * This action accepts data to import (in OpenDD format) and performs and import. It accepts
 * data as $data.
 *
 * @package Elgg
 * @subpackage Core
 */

$data = get_input('data', '', false);

$return = import($data);

if ($return) {
	system_message(elgg_echo('importsuccess'));
} else {
	register_error(elgg_echo('importfail'));
}

forward(REFERER);
