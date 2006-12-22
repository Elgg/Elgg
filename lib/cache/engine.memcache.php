<?php

// engine to talk to memcache (http://www.danga.com/memcached/)
// requires pecl memcached module

// set up connection
// return connection resource or false
function elggcache_cacheinit() {
    global $CFG, $messages;
    //memcache_debug(true);
    if (!empty($CFG->elggcache_enabled)) {
        static $memcacheconn;
        //var_dump($memcacheconn);
        if (!isset($memcacheconn)) {
            if (!empty($CFG->elggcache_debug)) {
                $messages[] = 'connecting to memcache<br />';
            }
            $memcacheconn = new Memcache;
            $memcacheconn->pconnect($CFG->elggcache_memcache_host, $CFG->elggcache_memcache_port);
        }
        //var_dump($memcacheconn);
        if (empty($memcacheconn) || !is_resource($memcacheconn->connection)) {
            $CFG->elggcache_enabled = false;
            if (!empty($CFG->elggcache_debug)) {
                $messages[] = 'failed connect to memcache<br />';
            }
        }
    } else {
        $memcacheconn = false;
    }
    
    //$stats = $memcacheconn->getExtendedStats();
    //var_dump($stats);
    //var_dump($memcacheconn);
    return $memcacheconn;
}

// retrieve an item from the cache
// $type = a class of item, to allow per-type operations
// $key = unique id of cached item
// return object or null
function elggcache_get($type, $key) {
    global $CFG, $messages;
    $returnvalue = null;
    if ($CFG->elggcache_enabled) {
        $memcacheconn = elggcache_cacheinit();
        $fullkey = $CFG->elggcache_memcache_keyprefix . $type . "_" . $key;
        
        // memcache get returns false on failure, so it's not really possible to store false 
        // unless we put each cache item inside an object/array
        if ($result = $memcacheconn->get($fullkey)) {
            $returnvalue = $result;
        }
    }
    return $returnvalue;
}

// store an item in the cache
// $type = a class of item, to allow per-type operations
// $key = unique id of cached item
// $data = value/array/object to be stored
// return true or false
function elggcache_set($type, $key, $data) {
    global $CFG, $messages;
    $returnvalue = false;
    if ($CFG->elggcache_enabled) {
        $memcacheconn = elggcache_cacheinit();
        $fullkey = $type . "_" . $key;
        
        if (!empty($CFG->elggcache_debug)) {
            $messages[] = 'setting memcache key ' . $fullkey . '<br />';
        }
        $returnvalue = $memcacheconn->set($CFG->elggcache_memcache_keyprefix . $fullkey, $data);
        if ($returnvalue) {
            _elggcache_typelistadd($type, $fullkey);
        }
    }
    return $returnvalue;
}

// delete an item from the cache
// $type = a class of item, to allow per-type operations
// $key = unique id of cached item
// return true or false
function elggcache_delete($type, $key) {
    global $CFG, $messages;
    $returnvalue = false;
    if ($CFG->elggcache_enabled) {
        $memcacheconn = elggcache_cacheinit();
        $fullkey = $type . "_" . $key;
        
        if (!empty($CFG->elggcache_debug)) {
            $messages[] = 'deleting memcache key ' . $fullkey . '<br />';
        }
        $returnvalue = $memcacheconn->delete($CFG->elggcache_memcache_keyprefix . $fullkey);
        
        if ($returnvalue) {
            _elggcache_typelistremove($type, $fullkey);
        }
    }
    return $returnvalue;
}

// purge entire cache contents
// return true or false
function elggcache_purge() {
    global $CFG, $messages;
    $returnvalue = false;
    if ($CFG->elggcache_enabled) {
        $memcacheconn = elggcache_cacheinit();
        if (!empty($CFG->elggcache_debug)) {
            $messages[] = 'purging memcache<br />';
        }
        $returnvalue = $memcacheconn->flush();
    }
    return $returnvalue;
}

// purge all cached items of a given type
/*
    based on the mysql query cache method of purging all cached data about a given 
    table whenever that table is modified.
    
    of course, it may be faster to just purge all data on all modification queries.
    it'd be nice to have something more fine grained, but as the even the most abstracted 
    datalib functions can take any old field-value lookup pairs, there's not really a unique
    key to go on.
    
    additionally, there's the problem that memcached does not guarantee data will be there,
    so the type mapping entries may be lost even though the entries they refer to are still
    cached, and thus can't then be purged by type.
*/
// $type = a class of item, to allow per-type operations
// return true or false
function elggcache_cachepurgetype($type) {
    global $CFG;
    $returnvalue = false;
    if ($CFG->elggcache_enabled) {
        $memcacheconn = elggcache_cacheinit();
        $fullkey = $CFG->elggcache_memcache_keyprefix . "typelist_" . $type;
        $typelist = $memcacheconn->get($fullkey);
        if (is_array($typelist)) {
            foreach($typelist as $acachekey) {
                $returnvalue = $memcacheconn->delete($CFG->elggcache_memcache_keyprefix . $fullkey);
            }
        }
        
    }
    return $returnvalue;
}


// these maintain arrays of keys for each "type", allowing operations by type
// private - do not call these directly
function _elggcache_typelistadd($type, $key) {
    
    global $CFG;
    $memcacheconn = elggcache_cacheinit();
    $fullkey = $CFG->elggcache_memcache_keyprefix . "typelist_" . $type;
    $typelist = $memcacheconn->get($fullkey);
    if (!is_array($typelist)) {
        $typelist = array();
    }
    $typelist[$key] = $key;
    $setbool = $memcacheconn->set($fullkey, $typelist);
    
}

function _elggcache_typelistremove($type, $key) {
    
    global $CFG;
    $memcacheconn = elggcache_cacheinit();
    $fullkey = $CFG->elggcache_memcache_keyprefix . "typelist_" . $type;
    $typelist = $memcacheconn->get($fullkey);
    if (is_array($typelist)) {
        unset($typelist[$key]);
        $setbool = $memcacheconn->set($fullkey, $typelist);
    }
    
}





?>