<?php
/**
 * Defines database creditials.
 *
 * Most of Elgg's configuration is stored in the database.  This file contains the
 * credentials to connect to the database, as well as a few optional configuration
 * values.
 *
 * The Elgg installation attempts to populate this file with the correct settings
 * and then rename it to settings.php.
 *
 * @todo Turn this into something we handle more automatically.
 * @package Elgg
 * @subpackage Core
 */

global $CONFIG;
if (!isset($CONFIG)) {
	$CONFIG = new stdClass;
}

/*
 * Standard configuration
 *
 * You will use the same database connection for reads and writes.
 * This is the easiest configuration, and will suit 99.99% of setups. However, if you're
 * running a really popular site, you'll probably want to spread out your database connections
 * and implement database replication.  That's beyond the scope of this configuration file
 * to explain, but if you know you need it, skip past this section.
 */

/**
 * The database username
 *
 * @global string $CONFIG->dbuser
 */
$CONFIG->dbuser = '{{CONFIG_DBUSER}}';

/**
 * The database password
 *
 * @global string $CONFIG->dbpass
 */
$CONFIG->dbpass = '{{CONFIG_DBPASS}}';

/**
 * The database name
 *
 * @global string $CONFIG->dbname
 */
$CONFIG->dbname = '{{CONFIG_DBNAME}}';

/**
 * The database host.
 *
 * For most installations, this is 'localhost'
 *
 * @global string $CONFIG->dbhost
 */
$CONFIG->dbhost = '{{CONFIG_DBHOST}}';

/**
 * The database prefix
 *
 * This prefix will be appended to all Elgg tables.  If you're sharing
 * a database with other applications, use a database prefix to namespace tables
 * in order to avoid table name collisions.
 *
 * @global string $CONFIG->dbprefix
 */
$CONFIG->dbprefix = '{{CONFIG_DBPREFIX}}';

/**
 * Multiple database connections
 *
 * Here you can set up multiple connections for reads and writes. To do this, uncomment out
 * the lines below.
 *
 * @todo Does this work?
 */

/*

// Yes! We want to split reads and writes
$CONFIG->db->split = true;

// READS
// Database username
$CONFIG->db['read']->dbuser = "";

// Database password
$CONFIG->db['read']->dbpass = "";

// Database name
$CONFIG->db['read']->dbname = "";

// Database server
// (For most configurations, you can leave this as 'localhost')
$CONFIG->db['read']->dbhost = "localhost";

// WRITES
// Database username
$CONFIG->db['write']->dbuser = "";

// Database password
$CONFIG->db['write']->dbpass = "";

// Database name
$CONFIG->db['write']->dbname = "";

// Database server
// (For most configurations, you can leave this as 'localhost')
$CONFIG->db['write']->dbhost = "localhost";

 */

/*
 * For extra connections for both reads and writes, you can turn both
 * $CONFIG->db['read'] and $CONFIG->db['write'] into an array, eg:
 *
 * 	$CONFIG->db['read'][0]->dbhost = "localhost";
 *
 * Note that the array keys must be numeric and consecutive, i.e., they start
 * at 0, the next one must be at 1, etc.
 */


/**
 * Memcache setup (optional)
 * This is where you may optionally set up memcache.
 *
 * Requirements:
 * 	1) One or more memcache servers (http://www.danga.com/memcached/)
 *  2) PHP memcache wrapper (http://uk.php.net/manual/en/memcache.setup.php)
 *
 * Note: Multiple server support is only available on server 1.2.1 or higher with PECL library > 2.0.0
 */
//$CONFIG->memcache = true;
//
//$CONFIG->memcache_servers = array (
//	array('server1', 11211),
//	array('server2', 11211)
//);

/**
 * Use non-standard headers for broken MTAs.
 *
 * The default header EOL for headers is \r\n.  This causes problems
 * on some broken MTAs.  Setting this to TRUE will cause Elgg to use
 * \n, which will fix some problems sending email on broken MTAs.
 *
 * @global bool $CONFIG->broken_mta
 */
$CONFIG->broken_mta = FALSE;
