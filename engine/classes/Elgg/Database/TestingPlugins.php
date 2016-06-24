<?php
namespace Elgg\Database;

/**
 * Testing version of Plugins, finds no plugins
 *
 * @access private
 */
class TestingPlugins extends Plugins {

	/**
	 * Returns no plugins
	 *
	 * @param string $status The status of the plugins. active, inactive, or all.
	 * @return \ElggPlugin[]
	 */
	function find($status = 'active') {
		return [];
	}
}
