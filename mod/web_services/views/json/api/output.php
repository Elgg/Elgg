<?php
/**
 * Elgg JSON output
 * This outputs the api results as JSON
 */

$result = $vars['result'];
$export = $result->export();
echo json_encode($export);
