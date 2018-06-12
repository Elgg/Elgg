<?php
/**
 * Elgg JSON exception
 * Displays a single exception
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['object'] An exception
 */

if (elgg_is_admin_logged_in()) {
	echo elgg_view('messages/exceptions/admin_exception', $vars);
	return;
}

$exception = elgg_extract('object', $vars);
if (!$exception instanceof Throwable) {
	return;
}

$result = new stdClass();
$result->error = get_class($exception);

echo json_encode($result);
