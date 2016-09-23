<?php
/**
 * Elgg Entity Extender.
 * This file contains ways of extending an Elgg entity in custom ways.
 *
 * @package Elgg.Core
 * @subpackage DataModel.Extender
 */

/**
 * Detect the value_type for a given value.
 * Currently this is very crude.
 *
 * @todo Make better!
 *
 * @param mixed  $value      The value
 * @param string $value_type If specified, overrides the detection.
 *
 * @return string
 */
function detect_extender_valuetype($value, $value_type = "") {
	if ($value_type === 'integer' || $value_type === 'text') {
		return $value_type;
	}

	// This is crude
	if (is_int($value)) {
		return 'integer';
	}

	// Catch floating point values which are not integer
	if (is_numeric($value)) {
		return 'text';
	}

	if (is_string($value) || (is_object($value) && method_exists($value, '__toString'))) {
		return 'text';
	}

	$type = gettype($value);
	_elgg_services()->logger->warn("Metadata and annotations store only integers and strings. $type given.");

	return 'text';
}
