<?php

/**
 * Elgg installation
 * Various functions to assist with installing and upgrading the system
 *
 * @package Elgg.Core
 * @subpackage Installation
 */

// @todo - remove this internal function as soon as it is pulled from elgg_view()
function is_installed() {
	global $CONFIG;
	return $CONFIG->installed;
}