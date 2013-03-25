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

$exception = $vars['object'];
$result = new stdClass();
$result->error = get_class($exception);
$result->message = $exception->getMessage();
echo json_encode($result);
