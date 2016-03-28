<?php
/**
 * Defines database credentials.
 *
 * Most of Elgg's configuration is stored in the database.  This file contains the
 * credentials to connect to the database, as well as a few optional configuration
 * values.
 *
 * The Elgg installation attempts to populate this file with the correct settings
 * and then rename it to settings.php.
 *
 * @todo Turn this into something we handle more automatically.
 * @package    Elgg.Core
 * @subpackage Configuration
 */

date_default_timezone_set('{{timezone}}');

global $CONFIG;
if (!isset($CONFIG)) {
	$CONFIG = new \stdClass;
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
$CONFIG->dbuser = '{{dbuser}}';

/**
 * The database password
 *
 * @global string $CONFIG->dbpass
 */
$CONFIG->dbpass = '{{dbpassword}}';

/**
 * The database name
 *
 * @global string $CONFIG->dbname
 */
$CONFIG->dbname = '{{dbname}}';

/**
 * The database host.
 *
 * For most installations, this is 'localhost'
 *
 * @global string $CONFIG->dbhost
 */
$CONFIG->dbhost = '{{dbhost}}';

/**
 * The database prefix
 *
 * This prefix will be appended to all Elgg tables.  If you're sharing
 * a database with other applications, use a database prefix to namespace tables
 * in order to avoid table name collisions.
 *
 * @global string $CONFIG->dbprefix
 */
$CONFIG->dbprefix = '{{dbprefix}}';

/**
 * Multiple database connections
 *
 * Elgg supports master/slave MySQL configurations. The master should be set as
 * the 'write' connection and the slave(s) as the 'read' connection(s).
 *
 * To use, uncomment the below configuration and update for your site.
 */
//$CONFIG->db['split'] = true;

//$CONFIG->db['write']['dbuser'] = "";
//$CONFIG->db['write']['dbpass'] = "";
//$CONFIG->db['write']['dbname'] = "";
//$CONFIG->db['write']['dbhost'] = "";

//$CONFIG->db['read'][0]['dbuser'] = "";
//$CONFIG->db['read'][0]['dbpass'] = "";
//$CONFIG->db['read'][0]['dbname'] = "";
//$CONFIG->db['read'][0]['dbhost'] = "";
//$CONFIG->db['read'][1]['dbuser'] = "";
//$CONFIG->db['read'][1]['dbpass'] = "";
//$CONFIG->db['read'][1]['dbname'] = "";
//$CONFIG->db['read'][1]['dbhost'] = "";

/**
 * Memcache setup (optional)
 * This is where you may optionally set up memcache.
 *
 * Requirements:
 * 	1) One or more memcache servers (http://www.danga.com/memcached/)
 *  2) PHP memcache wrapper (http://php.net/manual/en/memcache.setup.php)
 *
 * You can set a namespace prefix if you run multiple Elgg instances
 * on the same Memcache server.
 *
 * Note: Multiple server support is only available on server 1.2.1
 * or higher with PECL library > 2.0.0
 */
//$CONFIG->memcache = true;
//
//$CONFIG->memcache_servers = array (
//	array('server1', 11211),
//	array('server2', 11211)
//);

// namespace prefix
// $CONFIG->memcache_namespace_prefix = '';

/**
 * Better caching performance
 *
 * Configuring the location of your data directory and enabling simplecache in
 * the settings.php file improves caching performance. It allows Elgg to skip
 * connecting to the database when serving cached JavaScript and CSS files. If
 * you uncomment and configure these settings, you will not be able to change
 * them from the Elgg advanced settings page.
 */
//$CONFIG->dataroot = "";
//$CONFIG->simplecache_enabled = true;

/**
 * Enable the boot cache
 *
 * Elgg can store most non-user-specific boot up data in a cache. If you want to
 * enable this, uncomment the next line to set it to 10. Although Elgg has a built-
 * in invalidation strategy for this cache, you should consider a small TTL to
 * minimize the damage if the cache should grow stale.
 */
//$CONFIG->boot_cache_ttl = 10;

/**
 * Cookie configuration
 *
 * Elgg uses 2 cookies: a PHP session cookie and an extended login cookie
 * (also called the remember me cookie). See the PHP manual for documentation on
 * each of these parameters. Possible options:
 *
 *  - Set the session name to share the session across applications.
 *  - Set the path because Elgg is not installed in the root of the web directory.
 *  - Set the secure option to true if you only serve the site over HTTPS.
 *  - Set the expire option on the remember me cookie to change its lifetime
 *
 * To use, uncomment the appropriate sections below and update for your site.
 *
 * @global array $CONFIG->cookies
 */
// get the default parameters from php.ini
//$CONFIG->cookies['session'] = session_get_cookie_params();
//$CONFIG->cookies['session']['name'] = "Elgg";
// optionally overwrite the defaults from php.ini below
//$CONFIG->cookies['session']['path'] = "/";
//$CONFIG->cookies['session']['domain'] = "";
//$CONFIG->cookies['session']['secure'] = false;
//$CONFIG->cookies['session']['httponly'] = false;

// extended session cookie
//$CONFIG->cookies['remember_me'] = session_get_cookie_params();
//$CONFIG->cookies['remember_me']['name'] = "elggperm";
//$CONFIG->cookies['remember_me']['expire'] = strtotime("+30 days");
// optionally overwrite the defaults from php.ini below
//$CONFIG->cookies['remember_me']['path'] = "/";
//$CONFIG->cookies['remember_me']['domain'] = "";
//$CONFIG->cookies['remember_me']['secure'] = false;
//$CONFIG->cookies['remember_me']['httponly'] = false;


/**
 * Use non-standard headers for broken MTAs.
 *
 * The default header EOL for headers is \r\n.  This causes problems
 * on some broken MTAs.  Setting this to true will cause Elgg to use
 * \n, which will fix some problems sending email on broken MTAs.
 *
 * @global bool $CONFIG->broken_mta
 */
$CONFIG->broken_mta = false;

/**
 * Disable the database query cache
 *
 * Elgg stores each query and its results in a query cache.
 * On large sites or long-running scripts, this cache can grow to be
 * large.  To disable query caching, set this to true.
 *
 * @global bool $CONFIG->db_disable_query_cache
 */
$CONFIG->db_disable_query_cache = false;

/**
 * Minimum password length
 *
 * This value is used when validating a user's password during registration.
 *
 * @global int $CONFIG->min_password_length
 */
$CONFIG->min_password_length = 6;

/**
 * This is an optional script used to override Elgg's default handling of
 * uncaught exceptions.
 *
 * This should be an absolute file path to a php script that will be called
 * any time an uncaught exception is thrown.
 *
 * The script will have access to the following variables as part of the scope
 * global $CONFIG
 * $exception - the unhandled exception
 *
 * @warning - the database may not be available
 *
 * @global string $CONFIG->exception_include
 */
$CONFIG->exception_include = '';

/**
 * To enable profiling, uncomment the following lines, and replace __some_secret__ with a
 * secret key. When enabled, profiling data will show in the JS console.
 */
//if (isset($_REQUEST['__some_secret__'])) {
//
//	// send profiling data to the JS console?
//	$CONFIG->enable_profiling = true;
//
//	// profile all queries? A page with a ton of queries could eat up memory.
//	$CONFIG->profiling_sql = false;
//
//	// in the list, don't include times that don't contribute at least this much to the
//	// total time captured. .1% by default
//	$CONFIG->profiling_minimum_percentage = .1;
//}
