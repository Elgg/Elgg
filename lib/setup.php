<?php

// declare our globals.
global $db;
global $USER;
global $CFG;
global $SESSION;
global $PAGE;

/// First try to detect some attacks on older buggy PHP versions
if (isset($_REQUEST['GLOBALS']) || isset($_COOKIE['GLOBALS']) || isset($_FILES['GLOBALS'])) {
    die('Fatal: Illegal GLOBALS overwrite attempt detected!');
}

// set up perf.
init_performance_info();

/// Just say no to link prefetching (Moz prefetching, Google Web Accelerator, others)
/// http://www.google.com/webmasters/faq.html#prefetchblock

if (!empty($_SERVER['HTTP_X_moz']) && $_SERVER['HTTP_X_moz'] === 'prefetch'){
    header($_SERVER['SERVER_PROTOCOL'] . ' 404 Prefetch Forbidden');        
    trigger_error('Prefetch request forbidden.');
    exit;
}

// Privacy policy for IE, bless its cotton socks

header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');

// Set defaults for some variables

if (!isset($CFG->tagline)) {
    $CFG->tagline = "";
}
if (empty($CFG->debug)) {
    $CFG->debug = 0;
}
if (empty($CFG->publicinvite)) {
    $CFG->publicinvite = $CFG->publicreg;
}
if (empty($CFG->emailfilter)) {
    $CFG->emailfilter = "";
}
if (empty($CFG->maxusers)) {
    $CFG->maxusers = 0;
}
if (empty($CFG->walledgarden)) {
    $CFG->walledgarden = 0;
}

if (empty($CFG->framename)) {
    $CFG->framename = null;
}

if (empty($CFG->defaultlocale)) {
    $CFG->defaultlocale = 'en';
}

if (empty($CFG->disable_templatechanging)) {
    $CFG->disable_usertemplates = false;
}

if (empty($CFG->disable_templatechanging)) {
    $CFG->disable_templatechanging = false;
}

if (empty($CFG->disable_publiccomments)) {
    $CFG->disable_publiccomments = false;
}

if (empty($CFG->community_create_flag)) {
    $CFG->community_create_flag = "";
}

if (empty($CFG->curlpath)) {
    $CFG->curlpath = false;
}

if (empty($CFG->cookiepath)) {
    $pathcomponents = @parse_url($CFG->wwwroot);
    if (!empty($pathcomponents['path'])) {
        $CFG->cookiepath = $pathcomponents['path'];
    } else {
        $CFG->cookiepath = '/';
    }
    unset($pathcomponents);
}

if (empty($CFG->absmaxuploadsize)) {
    // absolute maximum allowed file upload size.
    // in most cases, apache or php will have lower limits configured, that cannot be overridden in code.
    $CFG->absmaxuploadsize = '20M';
}

$CFG->libdir = $CFG->dirroot . 'lib';

// set up our database connection
if ($CFG->debug & E_USER_ERROR) {
    require_once($CFG->dirroot . 'lib/adodb/adodb-errorhandler.inc.php');
}
require_once($CFG->dirroot . 'lib/adodb/adodb.inc.php'); // Database access functions

$db = &ADONewConnection($CFG->dbtype);
$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

error_reporting(0);  // Hide errors

if (!empty($CFG->dbpersist)) {    // Use persistent connection (default)
    $dbconnected = $db->PConnect($CFG->dbhost,$CFG->dbuser,$CFG->dbpass,$CFG->dbname);
} else {                                                     // Use single connection
    $dbconnected = $db->Connect($CFG->dbhost,$CFG->dbuser,$CFG->dbpass,$CFG->dbname);
}
if (! $dbconnected) {
    // In the name of protocol correctness, monitoring and performance
    // profiling, set the appropriate error headers for machine consumption
    if (isset($_SERVER['SERVER_PROTOCOL'])) { 
        // Avoid it with cron.php. Note that we assume it's HTTP/1.x
        header($_SERVER['SERVER_PROTOCOL'] . ' 503 Service Unavailable');        
    }
    // and then for human consumption...
    echo '<html><body>';
    echo '<table align="center"><tr>';
    echo '<td style="color:#990000; text-align:center; font-size:large; border-width:1px; '.
        '    border-color:#000000; border-style:solid; border-radius: 20px; border-collapse: collapse; '.
        '    -moz-border-radius: 20px; padding: 15px">';
    echo '<p>Error: Database connection failed.</p>';
    echo '<p>It is possible that the database is overloaded or otherwise not running properly.</p>';
    echo '<p>The site administrator should also check that the database details have been correctly specified in config.php</p>';
    echo '</td></tr></table>';
    echo '</body></html>';
    die;
} else {
    if ($db->databaseType == 'mysql') {
        $db->Execute("SET NAMES 'utf8'");
        $db->Execute("SET CHARSET 'utf8'");
    } else if ($db->databaseType == 'postgres7') {
        $db->Execute("SET NAMES 'utf8'");
    }
}

/// Load up any configuration from the config table
$METATABLES = $db->Metatables();
if ($METATABLES) {
    $CFG = get_config();
}

/// Turn on SQL logging if required
if (!empty($CFG->logsql)) {
    $db->LogSQL();
}


/// Set error reporting back to normal
if (empty($CFG->debug)) {
    $CFG->debug = 7;
}
error_reporting($CFG->debug);

/// File permissions on created directories in the $CFG->dataroot

if (empty($CFG->directorypermissions)) {
    $CFG->directorypermissions = 0777;      // Must be octal (that's why it's here)
}

/// Files might not want all the permissions that directories have, e.g. +x or g+s,
/// so using a separate setting for files
if (empty($CFG->filepermissions)) {
    $CFG->filepermissions = 0666;      // Must be octal
}

if (!is_writable($CFG->dataroot)) {
    $messages[] = "Your current dataroot directory, $CFG->dataroot is not writable by the webserver!";
}

/// Set up session handling
if(empty($CFG->respectsessionsettings)) {
    if (empty($CFG->dbsessions)) {   /// File-based sessions
        
        // Some distros disable GC by setting probability to 0
        // overriding the PHP default of 1
        // (gc_probability is divided by gc_divisor, which defaults to 1000)
        if (ini_get('session.gc_probability') == 0) {
            ini_set('session.gc_probability', 1);
        }
        
        if (!empty($CFG->sessiontimeout)) {
            ini_set('session.gc_maxlifetime', $CFG->sessiontimeout);
        }
        
        if (!file_exists($CFG->dataroot .'sessions')) {
            require_once($CFG->dirroot . 'lib/uploadlib.php');
            make_upload_directory('sessions');
        }
        ini_set('session.save_path', $CFG->dataroot .'sessions');
        
    } else {                         /// Database sessions
        ini_set('session.save_handler', 'user');
        
        $ADODB_SESSION_DRIVER  = $CFG->dbtype;
        $ADODB_SESSION_CONNECT = $CFG->dbhost;
        $ADODB_SESSION_USER    = $CFG->dbuser;
        $ADODB_SESSION_PWD     = $CFG->dbpass;
        $ADODB_SESSION_DB      = $CFG->dbname;
        $ADODB_SESSION_TBL     = $CFG->prefix.'sessions';
        
        require_once($CFG->libdir. '/adodb/session/adodb-session.php');
    }
}
/// Set sessioncookie variable if it isn't already
if (!isset($CFG->sessioncookie)) {
    $CFG->sessioncookie = '';
}

// for phpthumb
require_once($CFG->dirroot . 'lib/uploadlib.php');
make_upload_directory('cache/phpThumb');
// for magpie rss
make_upload_directory('cache/magpie');
define('MAGPIE_CACHE_DIR',$CFG->dataroot.'cache/magpie');

// Files
make_upload_directory('cache/files');

/// Configure ampersands in URLs

@ini_set('arg_separator.output', '&amp;');

/// Refuse to run with register_globals
if (ini_get_bool('register_globals')) {
    die("Elgg cannot run with register_globals on");
}

// Now we use prepared statements everywhere,
// we want everything to be stripslashed
// rather than addslashed.
if (ini_get_bool('magic_quotes_gpc') ) {
    
    //do keys as well, cos array_map ignores them
    function stripslashes_arraykeys($array) {
        if (is_array($array)) {
            $array2 = array();
            foreach ($array as $key => $data) {
                if ($key != stripslashes($key)) {
                    $array2[stripslashes($key)] = $data;
                } else {
                    $array2[$key] = $data;
                }
            }
            return $array2;
        } else {
            return $array;
        }
    }
    
    function stripslashes_deep($value) {
        if (is_array($value)) {
            $value = stripslashes_arraykeys($value);
            $value = array_map('stripslashes_deep', $value);
        } else {
            $value = stripslashes($value);
        }
        return $value;
    }
    
    $_POST = stripslashes_arraykeys($_POST);
    $_GET = stripslashes_arraykeys($_GET);
    $_COOKIE = stripslashes_arraykeys($_COOKIE);
    $_REQUEST = stripslashes_arraykeys($_REQUEST);
    
    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
    $_REQUEST = array_map('stripslashes_deep', $_REQUEST);
    if (!empty($_SERVER['REQUEST_URI'])) {
        $_SERVER['REQUEST_URI'] = stripslashes($_SERVER['REQUEST_URI']);
    }
    if (!empty($_SERVER['QUERY_STRING'])) {
        $_SERVER['QUERY_STRING'] = stripslashes($_SERVER['QUERY_STRING']);
    }
    if (!empty($_SERVER['HTTP_REFERER'])) {
        $_SERVER['HTTP_REFERER'] = stripslashes($_SERVER['HTTP_REFERER']);
    }
    if (!empty($_SERVER['PATH_INFO'])) {
        $_SERVER['PATH_INFO'] = stripslashes($_SERVER['PATH_INFO']);
    }
    if (!empty($_SERVER['PHP_SELF'])) {
        $_SERVER['PHP_SELF'] = stripslashes($_SERVER['PHP_SELF']);
    }
    if (!empty($_SERVER['PATH_TRANSLATED'])) {
        $_SERVER['PATH_TRANSLATED'] = stripslashes($_SERVER['PATH_TRANSLATED']);
    }
    
}

// wtf? $noelggcookie is never set - Sven
if (!isset($noelggcookie)) {
    session_name('ElggSession'.$CFG->sessioncookie);
    ini_set("session.cookie_path", $CFG->cookiepath);
    @session_start();
    if (! isset($_SESSION['SESSION'])) {
        $_SESSION['SESSION'] = new Stdclass;
        $_SESSION['SESSION']->session_test = random_string(10);
        if (!empty($_COOKIE['ElggSessionTest'.$CFG->sessioncookie])) {
            $_SESSION['SESSION']->has_timed_out = true;
        }
        setcookie('ElggSessionTest'.$CFG->sessioncookie, $_SESSION['SESSION']->session_test, 0, $CFG->cookiepath);
        $_COOKIE['ElggSessionTest'.$CFG->sessioncookie] = $_SESSION['SESSION']->session_test;
    }
    if (! isset($_SESSION['USER']))    {
        $_SESSION['USER']    = new StdClass;
    }
    
    $SESSION = &$_SESSION['SESSION'];   // Makes them easier to reference
    $USER    = &$_SESSION['USER'];
}
else {
    $SESSION = NULL;
    $USER    = NULL;
}

// Load textlib
require_once($CFG->dirroot . 'lib/textlib.class.php');

if (defined('FULLME')) {     // Usually in command-line scripts like admin/cron.php
    $FULLME = FULLME;
    $ME = FULLME;
} else {
    $FULLME = qualified_me();
    $ME = strip_querystring($FULLME);
}

/// In VERY rare cases old PHP server bugs (it has been found on PHP 4.1.2 running
/// as a CGI under IIS on Windows) may require that you uncomment the following:
//  session_register("USER");
//  session_register("SESSION");

/// now do a session test to prevent random user switching
if ($SESSION != NULL) {
    if (empty($_COOKIE['ElggSessionTest'.$CFG->sessioncookie])) {
        report_session_error();
    } else if (isset($SESSION->session_test) && $_COOKIE['ElggSessionTest'.$CFG->sessioncookie] != $SESSION->session_test) {
        report_session_error();
    }
}

if (!empty($CFG->opentogoogle)) {
    if (empty($_SESSION['USER'])) {
        if (!empty($_SERVER['HTTP_USER_AGENT'])) {
            if (strpos($_SERVER['HTTP_USER_AGENT'], 'Googlebot') !== false ) {
                $USER = guest_user();
            }
            if (strpos($_SERVER['HTTP_USER_AGENT'], 'google.com') !== false ) {
                $USER = guest_user();
            }
        }
        if (empty($_SESSION['USER']) and !empty($_SERVER['HTTP_REFERER'])) {
            if (strpos($_SERVER['HTTP_REFERER'], 'google') !== false ) {
                $USER = guest_user();
            } else if (strpos($_SERVER['HTTP_REFERER'], 'altavista') !== false ) {
                $USER = guest_user();
            }
        }
    }
}

/// Populates an empty $USER if is empty
if (empty($USER) || !isset($USER->ident)) {
    $USER = guest_user();
}

/// backwards compatibility
fill_legacy_user_session($USER);

//////
////// Load some core libraries
//////
require_once($CFG->dirroot . "lib/templates.php");
require_once($CFG->dirroot . "lib/displaylib.php");

//////
////// Init templating basics
//////
if (!isset($CFG->templatestore)) { $CFG->templatestore = 'db' ;}
if (!isset($CFG->templatesroot)) { $CFG->templatesroot = $CFG->dirroot . "mod/template/templates/";}
if (!isset($PAGE->menu       )) { $PAGE->menu        = array();}
if (!isset($PAGE->menu_sub   )) { $PAGE->menu_sub    = array();}
if (!isset($PAGE->menu_top   )) { $PAGE->menu_top    = array();}
if (!isset($PAGE->menu_bottom)) { $PAGE->menu_bottom = array();}

//////
////// Define what modules we have, and load their libraries
//////

// TODO : set up a modules table so we can do get_records('modules')
//        to fetch the enabled ones (instead of all the available modules)
//        we can also track db version with it. 
if ($allmods = get_list_of_plugins('mod') ) {
    foreach ($allmods as $mod) {
        $modfile = $CFG->dirroot . 'mod/'.$mod .'/lib.php';
        if (file_exists($modfile)) {
            include_once($modfile);
        }
    }
}
// keep the global scope clean
unset($allmods); unset ($mod); unset($modfile);

/// Apache log integration. In apache conf file one can use ${ELGGUSER}n in
/// LogFormat to get the current logged in username in Elgg.
/// NOTE: we are grabbing the username -- see the commented out lines
/// for alternative things that could be logged...
if ($USER && function_exists('apache_note')) {
    $apachelog_username = clean_filename($USER->username);
    // $apachelog_name     = clean_filename($USER->firstname. " ".$USER->lastname);
    // $apachelog_userid   = $USER->ident;
    /* Enable this commented out section ONLY if Elgg can do 
       user masquerading...
    if (isset($USER->realuser)) {
        if ($realuser = get_record('users', 'ident', $USER->realuser)) {
            $apachelog_username = clean_filename($realuser->username." as ".$apachelog_username);            
            // $apachelog_name = clean_filename($realuser->firstname." ".$realuser->lastname ." as ".$apachelog_name);
            // $apachelog_userid = clean_filename($realuser->id." as ".$apachelog_userid);
        }
    }
    */ 
    apache_note('ELGGUSER', $apachelog_username);
}

/// Adjust ALLOWED_TAGS
adjust_allowed_tags();

// backwards compatibility (this is what elgg used to use)
define("db_server", $CFG->dbhost);
define("db_user",$CFG->dbuser);
define("db_pass",$CFG->dbpass);
define("db_name",$CFG->dbname);

define("sitename", $CFG->sitename);
define("url",$CFG->wwwroot);
define("path",$CFG->dirroot);
define("email",$CFG->sysadminemail);
define("locale", $CFG->defaultlocale);
//define("public_reg", $CFG->publicreg);
if (empty($CFG->default_access)) {
    $CFG->default_access = "LOGGED_IN";
}
define("default_access",$CFG->default_access);

// figure out a noreply address if we don't have one.
if (empty($CFG->noreplyaddress)) { 
    $CFG->noreplyaddress = 'noreply@'.preg_replace('/([a-zA-z]*:\/\/)([a-zA-Z0-9-.]*)([:0-9]*)(\/*.*)/','$2',$CFG->wwwroot);
}

/***
 *** init_performance_info() {
 ***
 *** Initializes our performance info early.
 *** 
 *** Pairs up with get_performance_info() which is actually
 *** in moodlelib.php. This function is here so that we can 
 *** call it before all the libs are pulled in. 
 ***
 **/
function init_performance_info() {

    global $PERF;

    $PERF = new StdClass;
    $PERF->dbqueries = 0;   
    $PERF->logwrites = 0;
    if (function_exists('microtime')) {
        $PERF->starttime = microtime();
    }
    if (function_exists('memory_get_usage')) {
        $PERF->startmemory = memory_get_usage();
    }
    if (function_exists('posix_times')) {
        $PERF->startposixtimes = posix_times();  
    }
}

?>