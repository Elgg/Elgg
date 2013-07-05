<?php
/**
 * Cache handler.
 *
 * External access to cached CSS and JavaScript views. The cached file URLS
 * should be of the form: cache/<ts>/<viewtype>/<name/of/view> where
 * ts is an identifier that is updated every time the cache is flushed.
 * The simplest way to maintain a unique identifier is to use the lastcache
 * timestamp in Elgg's config object.
 *
 * @see elgg_register_simplecache_view()
 *
 * @package Elgg.Core
 * @subpackage Cache
 */

require_once dirname(dirname(__FILE__)) . '/classes/Elgg/CacheHandler.php';

require_once dirname(dirname(__FILE__)) . '/settings.php';
/* @var stdClass $CONFIG */

// dataroot must have trailing slash
// @todo need a lib with core functions that have no depedencies
if (isset($CONFIG->dataroot)) {
	$CONFIG->dataroot = rtrim($CONFIG->dataroot, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
}

$handler = new Elgg_CacheHandler($CONFIG);

$handler->handleRequest($_GET, $_SERVER);
