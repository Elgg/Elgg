<?php
/**
 * Elgg configuration/install script
 * @author Rolando Espinoza La Fuente <rho@prosoftpeople.com>
 */
    global $CFG;

    require(dirname(__FILE__).'/lib/config-defaults.php');

    require($CFG->dirroot.'lib/elgglib.php');
    require($CFG->dirroot.'mod/gettext/lib.php');

    global $db, $CFG;

    $version = '0.9';
    $messages = array();
    $configs = array();
    $config_ready = false;

    // Prevent access on already running configuration
    if (file_exists($CFG->dirroot.'config.php') && config_check_db()) {
        $config_ready = true;
        $messages[] = __gettext('You have already configured Elgg and can safely remove the file <code>install.php</code> from your installation.');
    }
    
    config_init();

    // workaround for backward compatibility
    if (!function_exists('__gettext')) {
        function __gettext($s) {
            return $s;
        }
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Elgg <?php echo $version ?> <?php echo __gettext('installation') ?></title>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<style type="text/css" media="all">
    /* globals */

    body {
        padding: 0;
        margin:0;
        text-align:center;
        font-family: "Lucida Grande", verdana, arial, helvetica, sans-serif;
        color: #666; /*333;*/
        background: #eee url(mod/template/images/bg2.gif);
        width:100%;
        font-size:90%;
        line-height:1.6em;
        margin: 0 auto;
        }

    a {
            color: #7289AF;
            font-family: "Lucida Grande", verdana, arial, helvetica, sans-serif;
            font-size:100%;
            text-decoration: none;
        }

    a:hover {
        text-decoration: underline;
    }
        
    p {
        font-size: 100%;
        color: #5e5d5c; 
        margin: 0;
        padding: 5px;
    }

    h1 {
           margin:0px 0px 15px 0px;
        padding:0px;
        font-size:120%;
        font-weight:900;
    }


    h2 {
        margin:0px 0px 5px 0px;
        padding:0px;
        font-size:100%
    }


    h3 {
        margin:0px 0px 5px 0px;
        padding:0px;
        font-size:100%
    }

    h4 {
        margin:0px 0px 5px 0px;
        padding:0px;
        font-size:100%
    }

    h5 {
        margin:0px 0px 5px 0px;
        padding:0px;
        color:#1181AA;
           background:#fff;
        font-size:100%
    }

    td p {
        
        font-size: 0.8em;
        margin-top: 15px;
        padding-top: 15px;
        
    }

    blockquote {
        padding: 0 1pc 1pc 1pc;
        border: 1px solid #ddd;
        background-color: #F0F0F0;
           color:#000;
        background-image: url("{$CFG->wwwroot}_templates/double-quotes.png");
        background-repeat: no-repeat;
        background-position: -10px -7px;
    }

    .checkbox {
     background: #fff;
     border: none;
     margin: 0;
     padding: 0;
    }

    label {
     margin: 0;
     padding: 0;
    }

    input {
       width: auto;
       font-size: 1em;
        line-height: 1.2em;
       font-weight:normal;
        padding: 2px 4px;
    }

    textarea {
       background-color: #FFF;
       color: #000;
       border: 1px solid #CCC;
       font-size: 1em;
       padding: 3px;
        margin: 0 0 5px;
        width:100%;
        height:360px;
      }

    .button {
      padding:2px;
      font-size:16px;
      width:50px;
   } 

    .config-form {
        border: 1px #ddd dotted;
        margin: 0 auto;
    }

    .form-item {
        padding: 10px;
        border-bottom: 1px #ddd dotted;
        clear:both;
    }

    .form-item label {
        float: left;
        text-align: right;
        font-weight: bold;
        width: 220px;
    }

    .form-item input {
        width: 150px;
    }

    .form-error {
        background: #f7dad8;
    }

    code {
        font-size: 1.2em;
    }

    .form-item input,
    .form-item select {
        margin-left: 10px;
        border: 1px #ccc solid;
        background: #eee;
        color: #333;
    }

    .form-item input:focus, 
    .form-tiem select:focus {
        background: #fff;
        color: #000;
    }

    .form-item span {
        display: block;
        font-size: 0.8em;
        color: #666;
        padding-top: 5px;
        padding-left: 230px;
        line-height: 1.2em;
    }

    /*
    Wraps the entire page 
    */

    #container {
        width: 780px;
        min-width: 750px;
         padding:0px;
         margin:0px;
         border:1px solid #ccc;
         background:#fff;
         margin: 10px auto;
        }

    /*
    HEADER 
    */

    #header {
        width: 100%;
        background: #fff url(mod/template/images/newelgg.gif) top left no-repeat;
        color:#1181AA;
        padding: 0px;
        margin: 0px;
        text-align: center;
     height:80px;
        }

    #header h1 {
        padding: 40px 0 0 0;
        margin: 0;
        color:#017FBC;
     font-size:1.4em;
        text-align: center;
        font-weight:bold;
        }    

    /*
    SITE CONTENT WRAPPER 
    */

    #content {
        margin: 0;
        padding: 20px;
        text-align: left;
        border: 0px solid #ccc;
        border-top: none;
        background-color: #fff;
        color:#000;
     font-size:90%;
     min-height:300px;
        width: 600px;
        margin: 0 auto;
    }

    #content h1 {
      border-bottom: 1px solid #666;
      padding:0px;
      margin:0px;
     }

    /*
      FOOTER 
    */

    #footer {
        text-align: right;
        padding: 2px 10px;
         border-top:1px solid #ccc;
         border-bottom:1px solid #ddd;
         font-size:0.8em;
        color: #555;
    }

    #footer a:hover {
       text-decoration:underline;
     }

    #footer a:link, #footer a:visited {
        text-align:right;
    }

    /*
      Important warning messages
    */

    .important{ 
        background:#F7DAD8;
        color:#000;
    }

    .important p{
     }


    /*
     System messages
    */

    #messages {
     text-align:left;
     background:#E7EDF3;
     border:1px solid #87B2E1;
     padding:10px;
     margin:10px;
    }

    #messages ul {
      margin:0;
      padding:0;
    }

    #messages li {
      list-style:none;
    }

    /*
       MISC 
    */

    .clearall {
        padding: 0px;
        clear: both;
        font-size: 0px;
        }

    .continuebutton {
        text-align: center;
        margin-top: 20px;
        font-size: 1em;
    }

    .notifyproblem {
        color: #900;
        font-size: 1.2em;
    }

    .notifysuccess {
        color: #0b0; 
        font-size: 1.2em;
    }

    .setup {
        color: #ccc;
        font-size: 0.8em;
    }

    hr {
        background: #ccc;
        color: #ccc;
        height: 1px;
        margin: 0;
        border: 0;
    }
</style>
</head>
<body>
<div id="container">

<div id="header">
    <h1><?php echo __gettext('Welcome to elgg installation') ?></h1>
</div><!-- #header -->

<div id="content">

<?php show_messages() ?>

<?php
 if (!$config_ready) {

    if (config_submitted() && config_validate()) {
        if (config_write_file()) {
            config_copy_htaccess();
?>
            <p><?php echo __gettext('Your configuration file has been saved, you can now proceed with the installation.') ?></p>

<?php if (is_writable(dirname(__FILE__))) { ?>
            <p><?php echo __gettext('Please revert your permissions on your <em>elgg directory</em> for more security.') ?></p>
<?php } else { ?>
            <p><?php echo __gettext('Please revert your permissions on <code>config.php</code> for more security.') ?></p>
<?php } ?>
            <div class="setup">
<?php
                if (!config_check_db()) {
                    echo '<div style="color:#900;">';
                    echo __gettext('<strong>Error:</strong> Database connection failed, please edit <code>config.php</code> to include the correct values. ');
                    echo '<br />&raquo; ' . __gettext('<a href="install.php">Try again installation wizard</a>.');
                    echo '</div>';
                } else {
                    //FIXME: workaround, does not work config_check_db global db
                    //
                    // reload config
                    include($CFG->dirroot.'config.php');
                    // setup database
                    require_once($CFG->dirroot.'lib/adodb/adodb.inc.php');

                    $db = &ADONewConnection($CFG->dbtype);
                    $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
                    @$db->Connect($CFG->dbhost, $CFG->dbuser, $CFG->dbpass, $CFG->dbname);
                    // 

                    if ($db->databaseType == 'mysql') {
                        $db->Execute('SET NAMES "utf8"');
                        $db->Execute('SET CHARSET "utf8"');
                    } elseif ($db->databaseType == 'postgres7') {
                        $db->Execute('SET NAMES "utf8"');
                    }

                    $METATABLES = $db->Metatables();
                    $_SESSION['userid'] = -1; // workaround

                    // set initial administrator username & password
                    $CFG->newsinitialusername = $_POST['admin'];
                    $CFG->newsinitialpassword = $_POST['adminpw'];
                    $CFG->sysadminemail = $_POST['adminemail'];

                    //To display message Success!
                    $CFG->debug = 8;

                    require_once($CFG->dirroot.'lib/cache/lib.php');
                    require_once($CFG->dirroot.'lib/constants.php');
                    require_once($CFG->dirroot.'lib/elgglib.php'); // already included
                    require_once($CFG->dirroot.'lib/datalib.php');
                    require_once($CFG->dirroot.'lib/userlib.php');
                    require_once($CFG->dirroot.'lib/dbsetup.php');
                }
?>
            </div>

<?php       } else { ?>
<p><?php echo __gettext('Your configuration file is not writable, please copy the following  content and save it as <code>config.php</code> inside your elgg installation.') ?></p>

    <textarea id="config-file" onclick="this.select()"><?php echo config_file() ?></textarea>

<?php
        }
        print_continue('index.php');
    } else {
        echo config_form();
    }
 } else {
    print_continue('index.php');
 }
?>

</div><!-- #content -->

<div id="footer">
<p>elgg <?php echo $version ?></p>
</div>

</div><!-- #container -->
</body>
</html>
<?php

/**
 * Display messages in html block if there is any
 */
function show_messages() {
    global $messages;
    if (!empty($messages)) {
        echo '<div id="messages">';
        echo implode('<br />', $messages);
        echo '</div>';
    }
}

/**
 * Initial checks
 */
function config_init() {
    global $config_ready;
    global $configs;

    // trim all post values and remove slashes
    foreach ($_POST as $k => $v) { $_POST[$k] = stripslashes(trim($v)); }

    // default values
    if (!isset($_POST['wwwroot'])) { $_POST['wwwroot'] = "http://" . preg_replace("#install\.php.*#","", $_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);}
    if (!isset($_POST['admin'])) { $_POST['admin'] = 'admin'; }
    if (!isset($_POST['dbtype'])) { $_POST['dbtype'] = 'mysql'; }
    if (!isset($_POST['dbhost'])) { $_POST['dbhost'] = 'localhost'; }
    if (!isset($_POST['dbuser'])) { $_POST['dbuser'] = 'elgg'; }
    if (!isset($_POST['dbpass'])) { $_POST['dbpass'] = ''; }
    if (!isset($_POST['dbname'])) { $_POST['dbname'] = 'elgg'; }
    if (!isset($_POST['prefix'])) { $_POST['prefix'] = 'elgg_'; }

    //FIXME: force admin username
    $_POST['admin'] = 'news';

    $configs = array();
    $configs['wwwroot']->name = __gettext('Web root');
    $configs['wwwroot']->desc = __gettext('External URL to the site (eg: http://elgg.boston.edu/). *MUST* have a final slash at end.');
    $configs['admin']->name = __gettext('Admin username');
    $configs['admin']->desc = __gettext('Initial administrator username');
    $configs['adminpw']->name = __gettext('Admin password');
    $configs['adminpw']->desc = __gettext('Initial administrator password, at least 6 chars.');
    $configs['adminemail']->name = __gettext('System email');
    $configs['adminemail']->desc = __gettext('Email address for system notifications.');
    $configs['dbtype']->name = __gettext('Database type');
    $configs['dbtype']->desc = __gettext('PostgreSQL and MySQL supported. But is highly recommended MySQL');
    $configs['dbhost']->name = __gettext('Database host');
    $configs['dbuser']->name = __gettext('Database username');
    $configs['dbpass']->name = __gettext('Database password');
    $configs['dbpass']->not_required = true;
    $configs['dbname']->name = __gettext('Database name');
    $configs['prefix']->name = __gettext('Database table prefix');

    if (config_submitted()) { config_validate(); }
    elseif (!$config_ready) { config_check_requirements(); }
}

/**
 * Check database connection
 *
 * @return bool
 */
function config_check_db() {
    global $db, $CFG;

    // reload config
    include($CFG->dirroot.'config.php');
    // setup database
    require_once($CFG->dirroot.'lib/adodb/adodb.inc.php');

    if (empty($CFG->dbhost) || empty($CFG->dbuser) || empty($CFG->dbname)) {
        $result = false;
    } else {
        $db = &ADONewConnection($CFG->dbtype);
        $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;

        $result = @$db->Connect($CFG->dbhost, $CFG->dbuser, $CFG->dbpass, $CFG->dbname);

        if (!$result) {
            unset($db);
        }
    }

    return $result;
}

/**
 * Check if system fills minimal requirements
 * @return bool
 * @todo check all needed requirements
 */
function config_check_requirements() {
    global $messages, $CFG;

    if (!extension_loaded('mysql')) {
        $messages[] = __gettext('<strong>Warning:</strong> Your system does not support <code>mysql</code>.');
    }

    if (!extension_loaded('pgsql')) {
        $messages[] = __gettext('<strong>Warning:</strong> Your system does not support <code>pgsql</code>.');
    }

    if (file_exists($CFG->dirroot . 'config.php')) {
        if (!is_writable($CFG->dirroot . 'config.php')) {
            $messages[] = __gettext("<strong>Error:</strong> please make sure the file <code>{$CFG->dirroot}config.php</code> is writable in order to save your configuration.");
        }
    } elseif (!is_writable($CFG->dirroot)) {
        $messages[] = __gettext("<strong>Error:</strong> please make sure the install directory <code>{$CFG->dirroot}</code> is writable in order to save your configuration.");
    }

    if (!is_writable($CFG->dataroot)) {
        $messages[] = __gettext("<strong>Error:</strong> please make sure the elgg data directory <code>{$CFG->dataroot}</code> is writable.");
    }

    if (!empty($messages)) {
        // add reload
        $messages[] = '&raquo; <a href="install.php">Check again</a>';
    }
}

/**
 * Displays html input for given config name
 * @param string $name
 *      Configuration name
 * @return string
 *      Html output
 */
function config_input($name) {
    switch ($name) {
        case 'dbtype':
            $output = "<select name=\"{$name}\">";
            foreach (array('mysql', 'postgres7') as $dbtype) {
                if ($dbtype == $_POST['dbtype']) $selected = 'selected="selected"';
                else $selected = '';

                $output .= "<option value=\"{$dbtype}\" {$selected}>{$dbtype}</option>";
            }
            $output .= "</select>";
            break;

        case 'admin':
            $output = "<input type=\"text\" name=\"{$name}\" value=\"{$_POST[$name]}\" disabled=\"disabled\" />";
            break;

        case 'adminpw':
        case 'dbpass':
            $output = "<input type=\"password\" name=\"{$name}\" value=\"\" />";
            break;

        default:
            $output = "<input type=\"text\" name=\"{$name}\" value=\"{$_POST[$name]}\" />";
            break;
    }

    return $output;
}

/**
 * Display html form for config values
 * @return string
 *      Html form
 */
function config_form() {
    global $configs;

    $output = '<form class="config-form" action="" method="post">';

    foreach ($configs as $k => $c) {
        $label = $c->name . ': ';
        $label .= empty($c->not_required) ? '*' : '&nbsp;';

        $extraclass = empty($c->error) ? '' : ' form-error';

        $output .= "<div class=\"form-item{$extraclass}\">";
        $output .= "<label>{$label}</label>";
        $output .= config_input($k);
        $output .= "<span>{$c->desc}</span>";
        $output .= '</div>';
    }

    $output .= '<p class="continuebutton"><input type="submit" name="submit" value="' . __gettext('Write config') .'" /></p>';
    $output .= '</form>';

    return $output;
}

/**
 * True if form submitted 
 * @return bool
 */
function config_submitted() {
    return isset($_POST['submit']);
}

/**
 * Check for valide config values 
 * @return bool
 */
function config_validate() {
    global $messages;
    global $configs;
    // only run once
    static $result;

    if (isset($result)) {
        return $result;
    }

    //$urlregex = '#https?://([\w\.-]+)+(:\d+)?(/([\w-_\./]*(\?\S+)?)?)?#'; //extended url regex
    $urlregex = '#^https?://[\w\.-]+(:\d+)?([\w-_~\./()\#@%;$\+=\\\|&]*)?/$#i';
    $hostregex = '#^[\w\.-]+(:\d+)?$#';

    if (!preg_match($urlregex, $_POST['wwwroot'])) {
        $messages[] = __gettext('<strong>Error:</strong> Web root incorrect, please enter a valid url.');
        $configs['wwwroot']->error = true;
    }

    if (!validate_username($_POST['admin'])) {
        $messages[] = __gettext('<strong>Error:</strong> Invalid administrator username, please try again.');
        $configs['admin']->error = true;
    }

    if (empty($_POST['adminpw']) || strlen($_POST['adminpw']) < 6) {
        $messages[] = __gettext('<strong>Error:</strong> Invalid administrator password. Please enter at least 6 chars.');
        $configs['adminpw']->error = true;
    }

    if (!validate_email($_POST['adminemail'])) {
        $messages[] = __gettext('<strong>Error:</strong> Invalid administrator email, please enter a valid email address.');
        $configs['adminemail']->error = true;
    }

    if (!in_array($_POST['dbtype'], array('mysql', 'postgres7'))) {
        $messages[] = __gettext('<strong>Error:</strong> Database type unknown.');
        $configs['dbtype']->error = true;
    } elseif (function_exists('extension_loaded')) {
        if ($_POST['dbtype'] == 'mysql' && !extension_loaded('mysql')) {
            $messages[] = __gettext('<strong>Error:</strong> Database type <em>(mysql)</em> not supported on your system.');
            $configs['dbtype']->error = true;
        } elseif ($_POST['dbtype'] == 'postgres7' && !extension_loaded('pgsql')) {
            $messages[] = __gettext('<strong>Error:</strong> Database type <em>(postgresql)</em> not supported on your system.');
            $configs['dbtype']->error = true;
        }
    }

    if (!preg_match($hostregex, $_POST['dbhost'])) {
        $messages[] = __gettext('<strong>Error:</strong> Database host invalid.');
        $configs['dbhost']->error = true;
    }

    if (empty($_POST['dbuser'])) {
        $messages[] = __gettext('<strong>Error:</strong> Database username empty.');
        $configs['dbuser']->error = true;
    }

    if (empty($_POST['dbpass'])) {
        //$messages[] = __gettext('<strong>Error:</strong> Database password empty.');
        // prefix could be empty
    }

    if (empty($_POST['dbname'])) {
        $messages[] = __gettext('<strong>Error:</strong> Database name empty.');
        $configs['dbname']->error = true;
    }

    if (empty($_POST['prefix'])) {
        // prefix could be empty
    }

    if (empty($messages)) {
        $result = true;
    } else {
        $result = false;
    }

    return $result;
}

/**
 * Try to write configuration file, return false if not successful
 * @return bool
 */
function config_write_file() {
    global $messages, $CFG;

    $config_file = 'config.php';

    if (is_writable($CFG->dirroot . $config_file) || is_writable($CFG->dirroot)) {
        $f = @fopen($CFG->dirroot . $config_file, 'w');

        if (!$f) {
            $messages[] = __gettext('Could not write configuration file in your elgg directory.');
        } else {
            // write file  
            fwrite($f, config_file());
            fclose($f);
        }

    } else {
        $messages[] = __gettext('Could not write configuration file in your elgg directory.');
    }

    if (empty($messages)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Try to copy htaccess-dist to .htaccess
 * @return bool
 */
function config_copy_htaccess() {
    $path = dirname(__FILE__) . '/';
    $src = $path . 'htaccess-dist';
    $dst = $path . '.htaccess';
    $result = false;

    if (!file_exists($dst) && is_readable($src) && is_writable($path)) {
        $htaccess = file_get_contents($src);
        // Try to set RewriteBase
        include($path.'config.php');
        if (!empty($CFG->wwwroot)) {
            $webpath = preg_replace("#^https?://[\w\.:-]+(/[\w-_\./]*)$#i", "$1", $CFG->wwwroot);
            if (!empty($webpath) && strpos($webpath, '://') === false && $webpath != '/') {
                $htaccess = preg_replace("|#RewriteBase /[^\w]|", "RewriteBase $webpath\n", $htaccess);
            }
        }

        $f = @fopen($dst, 'w');
        if ($f) {
            @fwrite($f, $htaccess);
            @fclose($f);
            $result = true;
        }
    }

    return $result;
}

/**
 * Configuration template file
 * @return string
 */
function config_file() {
    $output = <<< END
<?php
// ELGG system configuration parameters.
// You could override default values here, to see all available
// options see config-defaults.php
// Note: some values are override by the values stored in database
// through admin manager

// External URL to the site (eg http://elgg.bogton.edu/)

   \$CFG->wwwroot = "{$_POST['wwwroot']}"; // **MUST** have a final slash at the end

// Database configuration

    \$CFG->dbtype = "{$_POST['dbtype']}";
    \$CFG->dbhost = "{$_POST['dbhost']}";

    \$CFG->dbuser = "{$_POST['dbuser']}";
    \$CFG->dbpass = "{$_POST['dbpass']}";

    \$CFG->dbname = "{$_POST['dbname']}";
    \$CFG->prefix = "{$_POST['prefix']}";

//    \$CFG->sysadminemail = "{$_POST['adminemail']}";

// Settings for initial administrator, only used at installation time
    \$CFG->newsinitialusername = "{$_POST['admin']}";
    \$CFG->newsinitialpassword = "{$_POST['adminpw']}";

END;

    $output .= "\n?>"; // close php code

    return $output;
}
?>