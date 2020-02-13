<?php
/**
 * Elgg JSON exception
 * Displays a single exception
 *
 * @uses $vars['object'] An exception
 */

use Elgg\Exceptions\DatabaseException;

$exception = elgg_extract('object', $vars);
if (!$exception instanceof Throwable) {
	return;
}

$result = new stdClass();
$result->error = get_class($exception);
$result->ts = (int) elgg_extract('ts', $vars);
$result->message = $exception->getMessage();

if ($exception instanceof DatabaseException) {
	$result->query = $exception->getQuery();
	$result->params = $exception->getParameters();
} else {
	$result->trace = $exception->getTrace();
}

echo json_encode($result);
