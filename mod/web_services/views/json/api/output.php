<?php
/**
 * Elgg JSON output
 * This outputs the api results as JSON
 */

/* @var $result GenericResult */
$result = elgg_extract('result', $vars);

echo json_encode($result->export());
