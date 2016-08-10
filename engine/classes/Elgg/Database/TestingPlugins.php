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
	 * @param string $status    The status of the plugins. active, inactive, or all.
	 * @param mixed  $site_guid Optional site guid
	 * @return \ElggPlugin[]
	 */
	function find($status = 'active', $site_guid = null) {
		return [];
	}
}
