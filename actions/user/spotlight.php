<?php
/**
 * Close or open spotlight.
 *
 * @package Elgg.Core
 * @subpackage Spotlight
 * @todo This is deprecated in 1.8
 */

$closed = get_input('closed', 'true');
if ($closed != 'true') {
	$closed = false;
} else {
	$closed = true;
}

get_loggedin_user()->spotlightclosed = $closed;
// exit as this action is called through Ajax
exit;