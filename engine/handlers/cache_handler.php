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

$if_none_match = empty($_SERVER['HTTP_IF_NONE_MATCH']) ? '' : $_SERVER['HTTP_IF_NONE_MATCH'];
$request_var = empty($_GET['request']) ? '' : $_GET['request'];

$handler = new Elgg_CacheHandler($CONFIG, $request_var, $if_none_match);

$handler->handleRequest();
