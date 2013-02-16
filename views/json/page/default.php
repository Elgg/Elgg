<?php
/**
 * Elgg JSON output pageshell
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['body']
 */

header("Content-Type: application/json");

echo $vars['body'];

// backward compatibility
global $jsonexport;
if (isset($jsonexport)) {
	elgg_deprecated_notice("Using \$jsonexport to produce json output has been deprecated", 1.9);
	echo json_encode($jsonexport);
}
