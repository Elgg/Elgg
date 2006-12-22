<?php
// ELGG system configuration parameters.

// System constants: set values as necessary
// Supply your values within the second set of speech marks in the pair
// i.e., define("system constant name", "your value");

// Name of the site (eg Elgg, Apcala, University of Bogton's Social Network, etc)

    $CFG->sitename = 'My Elgg site';

// A tagline for your site (eg 'Social network for Bogton')

    $CFG->tagline = 'A social networking site';

// External URL to the site (eg http://elgg.bogton.edu/)
// NB: **MUST** have a final slash at the end

    $CFG->wwwroot = 'http://';

// Physical path to the files (eg /home/elggserver/httpdocs/)
// NB: **MUST** have a final slash at the end

    $CFG->dirroot = '';

// Email address of the system (eg elgg-admin@bogton.edu)

    $CFG->sysadminemail = '';

// Account 1, with username news, is initially administrator. This
// will be its initial password - UPON INSTALLATION, YOU SHOULD LOG
// IN AS NEWS AND CHANGE THE PASSWORD IMMEDIATELY.

    $CFG->newsinitialpassword = '';
    
// Country code to set language to if you have gettext installed
// To include new languages, save their compiled .mo gettext
// file into languages/country code/LC_MESSAGES/
// (the file within this folder must be called elgg.mo)
// An Elgg gettext template is included as /elgg.pot

    $CFG->defaultlocale = 'en_GB';

// The following should be set to false if you don't want the
// general public to be able to register accounts with your
// Elgg site.

    $CFG->publicreg = true;
    
// The following should be set to false if you don't want users
// to be able to invite new users into the system.

    $CFG->publicinvite = true;

// Set this to a positive number to cap the number of users in your
// installation, for example if you're testing and only have a certain number
// of resources available to you.

    $CFG->maxusers = 0;

// Set this to 1 to enable a walled garden - i.e., if you're not logged in,
// all you can see is the login page.

    $CFG->walledgarden = 0;

// If the following string is non-blank, it must be present within
// the domains of email addresses of people signing up. For example, 
// if you set it to yourinstitution.edu, a user with the email address
// foo@bar.yourinstitution.edu will be able to sign up.
// This rule will hold true for both public registrations and invitations
// from within the system (if either are enabled).

    $CFG->emailfilter = "";
    
// The following sets the default access level within the Elgg
// site. Possible values include:
//        PUBLIC        :: available to everyone
//        LOGGED_IN    :: available to logged in users only
//        PRIVATE        :: available to the user only

    $CFG->default_access = "LOGGED_IN";

// Set the following to true to force users to log in before they can post comments, overriding per-user option
// Handy sledgehammer-to-crack-a-nut to protect against comment spam.
    $CFG->disable_publiccomments = false;
    
// dataroot. this is where uploaded files will go (and sessions for now)
// This should be OUTSIDE your wwwroot.
// NB: **MUST** have a final slash at the end

    $CFG->dataroot = '';

// You may change these values to something else but you must ensure that
// the user the web server process runs as is able to read and write under
// these permissions.
//$CFG->directorypermissions = 0777;
//$CFG->filepermissions = 0666;

    $CFG->dbtype = 'mysql'; // for now
    $CFG->dbhost = 'localhost';
    $CFG->dbuser = '';
    $CFG->dbpass = '';
    $CFG->dbname = '';
    $CFG->dbpersist = false;

// The following will assume all your database tables have this value at the start 
// of their names. If you're upgrading from an earlier version of Elgg, you might 
// need to set this to $CFG->prefix = '';

    $CFG->prefix = 'elgg';

// performance and debugging //
// Uncomment this to get sql errors sent to the webserver error log.
// $CFG->dblogerror = true;
// put this to 2047 to get adodb error handling.

    $CFG->debug = 0;

// Number of days to keep incoming RSS feed entries for before deleting them.
// A value of 0 disables automatic deletion.

    $CFG->rsspostsmaxage = 0;

//
//   Capture performance profiling data
//   define('ELGG_PERF'  , true);
//
//   Capture additional data from DB
//   define('ELGG_PERFDB'  , true);
//
//   Print to log (for passive profiling of production servers)
//   define('ELGG_PERFTOLOG'  , true);
//
//   Print to footer (works with the default theme)
//   define('ELGG_PERFTOFOOT', true);
//
// EMAIL HANDLING
// $CFG->smtphosts= ''; // empty (sendmail), qmail (qmail) or hosts
// $CFG->smtpuser = ''; // if using smtphosts, optional smtpuser & smtppass
// $CFG->smtppass = ''; 
// $CFG->noreplyaddress = ''; // this will default to noreply@hostname (from wwwroot)

// CLAMAV HANDLING
//$CFG->runclamonupload = true;
//$CFG->quarantinedir = '/somewhere/the/webserver/can/write/to';
//$CFG->clamfailureonupload = 'actlikevirus'; // OR 'donothing';
//$CFG->pathtoclam = '/usr/bin/clamscan'; // OR '/usr/bin/clamdscan';

// TEMPLATES HANDLING
//$CFG->disable_usertemplates = true;  // users can only choose from available templates
//$CFG->disable_templatechanging = true; // users can't change their template at all
//Templates root defaults to /_templates; if you change this, you will need
//to move or copy /_templates/Default_Template/ to the new location
//$CFG->templatesroot = '/some/path/'; // use on-disk templates instead of DB templates 

//Template file handing is not currently recommended.
//$CFG->templatestore = 'db';          // 'db' (default) or 'files' (requires $CFG->templatesroot to be set)

// set up some LMS hosts.
// --------------------------------------------------
// This array is KEYED on installid - the lms clients should identify themselves with this installid
// Token is required and should be shared with the lms client.
// Baseurl is required and will be used to link back to the lms.
// Name is optional and will be used to display a user friendly name.  The institution name is a good choice.
//      If this is not given, installid will be used instead.
// Confirmurl is optional (pings back confirmation of requests for signup and authentication.)
//      Moodle's confirm url is http://yourmoodlehost.com/blocks/eportfolio/confirm.php
//      But not all lms systems will implement this necessarily.
// Network address is optional (performs further checking on requests from the lms) & can be three different formats:
//      A full exact address like 192.168.0.1
//      A partial address like 192.168
//      CIDR notation, such as 231.54.211.0/20 
//
// $CFG->lmshosts = array('installid' => array('token' => 'sharedsecret', 'networkaddress' => 'xxx.xxx.xxx.xxx','confirmurl' => 'http://thelms.com/something.php', 'baseurl' => 'http://thelms.com', 'name' => 'Something Friendly'));
//
// Note that if you are going to allow file transfers between your lms and elgg using scp
// you will need to obtain the .pub part of an ssh key that the lms has been set up to use, 
// and add it to the ~/.ssh/authorized_keys file for the user on this machine they need to connect to, 
// and provide the lms with the username for that user. 
// This user needs write access to {$CFG->dataroot}lms/incoming/ as that is where the incoming files will end up.


// For SSL feed *reading* support, Snoopy needs to know where the curl executable is.
// To disable, or if curl is not available (e.g. on Windows), set to false
//$CFG->curlpath = "/usr/bin/curl";
$CFG->curlpath = false;


// Some other $CFG variables found in codebase

// $CFG->admin
// $CFG->allowobjectembed
// $CFG->aspellpath
// $CFG->auth
// $CFG->cachetext
// $CFG->currenttextiscacheable
// $CFG->dbsessions
// $CFG->detect_unchecked_vars
// $CFG->editorbackgroundcolor
// $CFG->editorfontfamily
// $CFG->editorfontlist
// $CFG->editorfontsize
// $CFG->editorhidebuttons
// $CFG->editorkillword
// $CFG->editorspelling
// $CFG->filterall
// $CFG->framename
// $CFG->handlebounces
// $CFG->ignoresesskey
// $CFG->lang
// $CFG->lastcron
// $CFG->libdir
// $CFG->logsql
// $CFG->maxbytes
// $CFG->newsclient_lastcron
// $CFG->openid_comments_allowed
// $CFG->opentogoogle
// $CFG->pathtodu
// $CFG->perfdebug
// $CFG->pixpath
// $CFG->plugins->editor
// $CFG->plugins->tinymce
// $CFG->release
// $CFG->respectsessionsettings
// $CFG->secureforms
// $CFG->session_error_counter
// $CFG->sessioncookie
// $CFG->sessiontimeout
// $CFG->templatedir
// $CFG->tracksessionip
// $CFG->unzip
// $CFG->userlocale
// $CFG->version
// $CFG->zip

?>