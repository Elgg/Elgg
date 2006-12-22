<?php
/*
    library to allow global caching of db table rows or complex objects, ideally in memcached.
    pluggable to allow default engine to be a noop, meaning hooks in elgglib/datalib can 
    be transparent if no cache installed.
*/

global $CFG;

if (empty($CFG->elggcache_enabled) || empty($CFG->elggcache_engine)) {
    $CFG->elggcache_enabled = false;
    $CFG->elggcache_engine = "noop";
}

// load engine
require_once($CFG->dirroot.'lib/cache/engine.' . $CFG->elggcache_engine . '.php');
elggcache_cacheinit();

?>
