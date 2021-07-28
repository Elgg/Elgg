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
 * The full file path for Elgg data storage. E.g. "/path/to/elgg-data/"
 *
 * @global string $CONFIG->dataroot
 */
$CONFIG->dataroot = "{{dataroot}}";

/**
 * The installation root URL of the site. E.g. "https://example.org/elgg/"
 *
 * If not provided, this is sniffed from the Symfony Request object
 *
 * @global string $CONFIG->wwwroot
 */
$CONFIG->wwwroot = "{{wwwroot}}";

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
 * The database port.
 *
 * For most installations, this is 3306
 *
 * @global string $CONFIG->dbport
 */
$CONFIG->dbport = '{{dbport}}';

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
 * The database encoding.
 *
 * If installing a fresh instance of Elgg 3.x or later, this MUST be set to "utf8mb4".
 * If you've upgraded an earlier Elgg version, do not set this until you have
 * manually converted your Elgg tables to utf8mb4.
 *
 * @global string $CONFIG->dbencoding
 */
$CONFIG->dbencoding = 'utf8mb4';

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
//$CONFIG->db['write']['dbport'] = "";

//$CONFIG->db['read'][0]['dbuser'] = "";
//$CONFIG->db['read'][0]['dbpass'] = "";
//$CONFIG->db['read'][0]['dbname'] = "";
//$CONFIG->db['read'][0]['dbhost'] = "";
//$CONFIG->db['read'][0]['dbport'] = "";
//$CONFIG->db['read'][1]['dbuser'] = "";
//$CONFIG->db['read'][1]['dbpass'] = "";
//$CONFIG->db['read'][1]['dbname'] = "";
//$CONFIG->db['read'][1]['dbhost'] = "";
//$CONFIG->db['read'][1]['dbport'] = "";

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
 * Redis setup (optional)
 * This is where you may optionally set up Redis.
 */
//$CONFIG->redis = true;
//
//$CONFIG->redis_options = array (
//	'database' => '', // The "database" option lets developers specific which specific database to use.
//	'password' => '', // The "password" option is used for clusters which required authentication.
//);
//
//$CONFIG->redis_servers = array (
//	array('server1', 6379),
//	array('server2', 6379)
//);

/**
 * Better caching performance
 *
 * Configuring simplecache in the settings.php file improves caching performance.
 * It allows Elgg to skip connecting to the database when serving cached JavaScript
 * and CSS files. If you uncomment and configure these settings, you will not be able
 * to change them from the Elgg advanced settings page.
 */
//$CONFIG->simplecache_enabled = true;

/**
 * Configure the boot cache TTL
 *
 * Elgg can store most non-user-specific boot up data in a cache. If you want to
 * configure how long Elgg takes before invalidating this cache, uncomment the next line
 * and set it to a number of seconds. If not set Elgg will default to 3600 seconds.
 */
//$CONFIG->boot_cache_ttl = 3600;

/**
 * Set cache directory
 *
 * By default, Elgg uses the data directory to store cache files, but this may
 * be undesirable for sites with the data directory on a distributed file system
 * (e.g. multiple servers with load balancing). You can specify a separate location
 * for the cache files here.
 */
//$CONFIG->cacheroot = "";

/**
 * Set local cache directory
 *
 * By default, Elgg uses the cache directory to store cache files, but this may
 * be undesirable for sites with a cache location on a network share used by multiple webservers.
 * You can specify a separate location for the local cache files here.
 */
//$CONFIG->localcacheroot = "";

/**
 * Set views simplecache directory
 *
 * Elgg uses the asset directory to store cached asset files.
 * By default, assets are stored in the cache root and site owners are
 * advised to symlink project root /cache to asset root.
 * Using this config value, you can change the default behavior
 */
//$CONFIG->assetroot = "";

/**
 * Plugins with more than the configured number of plugin settings won't be loaded into
 * bootdata cache. This is done to prevent memory issues.
 *
 * If set to < 1 all plugins will be loaded into the bootdata cache
 *
 * Default: 40
 */
//$CONFIG->bootdata_plugin_settings_limit = 0;

/**
 * Enable SendFile file serving
 *
 * After enabling X-Sendfile/X-Accel on your server, you can enable its support in Elgg. Set the
 * X-Sendfile-Type value to "X-Sendfile" (Apache) or "X-Accel-Redirect" (Nginx).
 *
 * @global string $CONFIG->{'X-Sendfile-Type'}
 */
//$CONFIG->{'X-Sendfile-Type'} = '';

/**
 * Configure X-Accel on nginx (see SendFile above)
 *
 * For Nginx, you'll likely also need to set this to a mapping like: "/path/to/dataroot/=/download/".
 *
 * @global string $CONFIG->{'X-Accel-Mapping'}
 */
//$CONFIG->{'X-Accel-Mapping'} = '';

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
 * Automatically disable plugins that are unable to boot
 *
 * Elgg will disable unbootable plugins. If you set this to false plugins
 * will no longer be disabled if they are not bootable. This could cause requests
 * to your site to fail as required views, classes or cached data could be missing.
 *
 * Setting this to false could be useful during deployment of new code.
 *
 * @global bool $CONFIG->auto_disable_plugins
 */
$CONFIG->auto_disable_plugins = true;

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

/**
 * Maximum php execution time for actions (in seconds)
 *
 * This setting can be used to set a custom default php execution time only for all registered Elgg actions.
 * Note that if some actions set their own execution time limit, this setting will no affect those actions.
 *
 * @global int $CONFIG->action_time_limit
 */
$CONFIG->action_time_limit = 120;

/**
 * Allow access to PHPInfo
 *
 * This setting can be used to allow site administrators access to the PHPInfo page.
 * By default this is not allowed.
 *
 * @global bool $CONFIG->allow_phpinfo
 */
$CONFIG->allow_phpinfo = false;

/**
 * Configure image processor
 *
 * This setting can be used to select a different image processor. By default the GD library is used.
 * Currently only 'imagick' is supported as a different configuration.
 * For Imagick the 'imagick' extension is required.
 *
 * @global string $CONFIG->image_processor
 */
//$CONFIG->image_processor = 'imagick';

/**
 * Email subject length limit
 *
 * The length limit for email subjects, defaults to 998 as described in http://www.faqs.org/rfcs/rfc2822.html
 *
 * @global int $CONFIG->emailer_transport
 */
//$CONFIG->email_subject_limit = 998;

/**
 * Configure emailer transport
 *
 * This setting can be used to select a different emailer transport. By default the Laminas Sendmail Transport is used.
 * Currently only 'smtp' and 'sendmail' are supported as a different configuration.
 * For 'smtp', the SMTP server's settings must be set, while 'sendmail' requires no configuration.
 *
 * @global string $CONFIG->emailer_transport
 */
//$CONFIG->emailer_transport = 'sendmail';

/**
 * Configure sendmail related settings
 */
//$CONFIG->emailer_sendmail_settings = '';

/**
 * Configure emailer SMTP settings
 *
 * This setting is only necessary if the above emailer transport is set to 'smtp'.
 * Please refer to https://docs.laminas.dev/laminas-mail/transport/smtp-options/#configuration-options
 * and https://docs.laminas.dev/laminas-mail/transport/smtp-authentication/#examples
 */
//$CONFIG->emailer_smtp_settings = array(
//	'name'              => 'localhost.localdomain',
//	'host'              => '127.0.0.1',
//	'port'              => 25,
//	'connection_class'  => 'login',
//	'connection_config' => [
//		'username' => 'user',
//		'password' => 'pass',
//		'ssl'      => '', // OPTIONAL (tls or ssl)
//		'port'     => '', // OPTIONAL (Non-SSL default 25, SSL default 465, TLS default 587)
//		'use_complete_quit' => '', // OPTIONAL
//	],
//);

/**
 * Configure notification queue delay
 *
 * This setting can be used to delay the processing of queued notifications. This can help when users create content and
 * quickly remove the content. A notification could be send out to subscribers about content which will be removed quickly
 *
 * The setting needs to be the number of seconds to delay the notification queue processing (eg. 3 minutes => 180 seconds)
 * Default: 0 (no delay)
 */
//$CONFIG->notifications_queue_delay = 180;

/**
 * Proxy configuration
 *
 * These settings can be used whenever there is the need to (optionally) configure a proxy
 */
$CONFIG->proxy = [
// 	'host' => '127.0.0.1',
// 	'port' => 25,
// 	'verify_ssl' => false,
// 	'username' => 'user',
// 	'password' => 'pass',
];

/**
 * Logging level
 *
 * By default, the logging level at boot-time is calculated from PHP's error_reporting(), and during boot
 * it is changed to the value specified on the Advanced Settings page. INFO-level events like DB queries
 * will not be logged during the initial boot.
 *
 * However, if the level is set here, it will be used during the entire request. It can be set to one of
 * the string levels in Elgg\Logger or ''. E.g., use 'INFO' to log all DB queries during boot up.
 */
//$CONFIG->debug = 'INFO';

/**
 * Language to locale mapping
 *
 * Some features support mapping a language to a locale setting (for example date presentations). In this setting
 * the mapping between language (key) and locale setting (values) can be configured.
 *
 * For example if you wish to present English dates in USA format make the mapping 'en' => ['en_US'], or if you
 * wish to use UK format 'en' => ['en_UK'].
 *
 * It's possible to configure the locale mapping for mulitple languages, for example:
 * [
 * 	'en' => ['en_US', 'en_UK'],
 * 	'nl' => ['nl_NL'],
 * ]
 *
 * It's also possible to add new languages to the supported languages
 * [
 * 	'my_language' => [], // no locale mapping
 * 	'my_language2' => ['en_US'], // using USA locale mapping
 * ]
 *
 * @see https://secure.php.net/manual/en/function.setlocale.php
 */
//$CONFIG->language_to_locale_mapping = [];

/**
 * Control if you want site language to be detected by browser language.
 */
//$CONFIG->language_detect_from_browser = true;

/**
 * When your webserver is behind a loadbalancer or reverse proxy server some client information (IP, protocol, etc) is
 * stored in different headers. For Elgg to be able to access these headers you need to configure the IP addresses of
 * the loadbalancer/reverse proxy.
 *
 * @see https://symfony.com/doc/3.3/deployment/proxies.html
 */
//$CONFIG->http_request_trusted_proxy_ips = [
//	'ip-address-1',
//	'ip-address-2',
//];

/**
 * When your webserver is behind a loadbalancer or reverse proxy server some client information (IP, protocol, etc) is
 * stored in different headers. For Elgg to be able to access these headers you need to configure the headers it's allowed to read.
 * This is a bitwise flag of the allowed headers, if nothing is configured all commonly used headers are allowed.
 *
 * @see https://symfony.com/doc/3.3/deployment/proxies.html
 */
//$CONFIG->http_request_trusted_proxy_headers = '';
