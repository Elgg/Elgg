<?php

/**
 * Elgg installation
 * Various functions to assist with installing and upgrading the system
 *
 * @package Elgg.Core
 * @subpackage Installation
 */

// these were internal functions that perhaps can be removed rather than deprecated
function is_db_installed() {
	elgg_deprecated_notice('is_db_installed() has been deprecated', 1.8);
	return true;
}

function is_installed() {
	elgg_deprecated_notice('is_installed() has been deprecated', 1.8);
	return true;
}

