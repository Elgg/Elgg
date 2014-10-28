<?php
/**
 * Elgg sites
 * Functions to manage multiple or single sites in an Elgg install
 *
 * @package Elgg.Core
 * @subpackage DataModel.Site
 */

/**
 * Get an ElggSite entity (default is current site)
 *
 * @param int $site_guid Optional. Site GUID.
 *
 * @return ElggSite
 * @since 1.8.0
 */
function elgg_get_site_entity($site_guid = 0) {
	global $CONFIG;

	$result = false;

	if ($site_guid == 0) {
		$site = $CONFIG->site;
	} else {
		$site = get_entity($site_guid);
	}

	if ($site instanceof ElggSite) {
		$result = $site;
	}

	return $result;
}

/**
 * Return the site specific details of a site by a row.
 *
 * @param int $guid The site GUID
 *
 * @return mixed
 * @access private
 */
function get_site_entity_as_row($guid) {
	global $CONFIG;

	$guid = (int)$guid;
	return get_data_row("SELECT * FROM {$CONFIG->dbprefix}sites_entity WHERE guid = $guid");
}

/**
 * Return the site via a url.
 *
 * @param string $url The URL of a site
 *
 * @return mixed
 */
function get_site_by_url($url) {
	global $CONFIG;

	$url = sanitise_string($url);

	$row = get_data_row("SELECT * from {$CONFIG->dbprefix}sites_entity where url='$url'");

	if ($row) {
		return get_entity($row->guid);
	}

	return false;
}

/**
 * Unit tests for sites
 *
 * @param string $hook   unit_test
 * @param string $type   system
 * @param mixed  $value  Array of tests
 * @param mixed  $params Params
 *
 * @return array
 * @access private
 */
function _elgg_sites_test($hook, $type, $value, $params) {
	global $CONFIG;
	$value[] = "{$CONFIG->path}engine/tests/ElggSiteTest.php";
	return $value;
}

elgg_register_plugin_hook_handler('unit_test', 'system', '_elgg_sites_test');
