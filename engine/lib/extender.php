<?php
/**
 * Elgg Entity Extender.
 * This file contains ways of extending an Elgg entity in custom ways.
 *
 * @package Elgg.Core
 * @subpackage DataModel.Extender
 */

/**
 * Alias of ElggExtender::detectValueType
 *
 * @param mixed  $value      The value
 * @param string $value_type If specified, overrides the detection.
 *
 * @return string
 * @todo delete in 3.0
 * @deprecated This will be removed in Elgg 3.0
 */
function detect_extender_valuetype($value, $value_type = "") {
	elgg_deprecated_notice(__FUNCTION__ . ' will be removed in Elgg 3.0. Do not use it.', '2.3');

	return ElggExtender::detectValueType($value, $value_type);
}
