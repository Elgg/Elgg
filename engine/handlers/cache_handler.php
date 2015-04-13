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

$autoloader = require_once(__DIR__ . '/../../autoloader.php');

require_once dirname(dirname(__FILE__)) . '/settings.php';
/* @var \stdClass $CONFIG */

$handler = new \Elgg\CacheHandler(new Elgg\Application());
$handler->handleRequest($_GET, $_SERVER);
