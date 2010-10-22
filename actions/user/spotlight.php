<?php
/**
 * Close or open spotlight.
 *
 * @package Elgg
 * @subpackage Core
 */

gatekeeper();

$closed = get_input('closed','true');
if ($closed != 'true') {
	$closed = false;
} else {
	$closed = true;
}

get_loggedin_user()->spotlightclosed = $closed;
// exit as this action is called through Ajax
exit;