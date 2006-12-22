<?php

// minimum required functions for cache engine

function elggcache_cacheinit() {
    return false;
}

// retrieve an item from the cache
// $type = a class of item, to allow per-type operations
// $key = unique id of cached item
// return object or null
function elggcache_get($type, $key) {

}

// store an item in the cache
// $type = a class of item, to allow per-type operations
// $key = unique id of cached item
// $data = value/array/object to be stored
// return true or false
function elggcache_set($type, $key, $data) {
    return false;
}

// delete an item from the cache
// $type = a class of item, to allow per-type operations
// $key = unique id of cached item
// return true or false
function elggcache_delete($type, $key) {
    return false;
}

// purge entire cache contents
// return true or false
function elggcache_purge() {
    return false;
}

// purge all cached items of a given type
/*
    based on the mysql query cache method of purging all cached data about a given 
    table whenever that table is modified.
    
    of course, it may be faster to just purge all data on all modification queries.
    it'd be nice to have something more fine grained, but as the even the most abstracted 
    datalib functions can take any old field-value lookup pairs, there's not really a unique
    key to go on.
*/
// $type = a class of item, to allow per-type operations
// return true or false
function elggcache_cachepurgetype($type) {
    return false;
}




?>