<?php

/**
 * Library of functions for handling input validation 
 * and some HTML generation
 * This library is a combination of bits of lib/weblib.php
 * and lib/moodlelib.php from moodle
 * http://moodle.org || http://sourceforge.net/projects/moodle
 * Copyright (C) 2001-2003  Martin Dougiamas  http://dougiamas.com 
 * @author Martin Dougiamas and many others
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */


/// PARAMETER HANDLING ////////////////////////////////////////////////////

/**
 * Returns a particular value for the named variable, taken from
 * POST or GET.  If the parameter doesn't exist then an error is
 * thrown because we require this variable.
 *
 * This function should be used to initialise all required values
 * in a script that are based on parameters.  Usually it will be
 * used like this:
 *    $id = required_param('id');
 *
 * @param string $varname the name of the parameter variable we want
 * @param int $options a bit field that specifies any cleaning needed
 * @return mixed
 */
function required_param($varname, $options=PARAM_CLEAN) {

    // detect_unchecked_vars addition
    global $CFG;
    if (!empty($CFG->detect_unchecked_vars)) {
        global $UNCHECKED_VARS;
        unset ($UNCHECKED_VARS->vars[$varname]);
    }

    if (isset($_POST[$varname])) {       // POST has precedence
        $param = $_POST[$varname];
    } else if (isset($_GET[$varname])) {
        $param = $_GET[$varname];
    } else {
        error('A required parameter ('.$varname.') was missing');
    }

    return clean_param($param, $options);
}

/**
 * Returns a particular value for the named variable, taken from
 * POST or GET, otherwise returning a given default.
 *
 * This function should be used to initialise all optional values
 * in a script that are based on parameters.  Usually it will be
 * used like this:
 *    $name = optional_param('name', 'Fred');
 *
 * @param string $varname the name of the parameter variable we want
 * @param mixed  $default the default value to return if nothing is found
 * @param int $options a bit field that specifies any cleaning needed
 * @return mixed
 */
function optional_param($varname, $default=NULL, $options=PARAM_CLEAN) {

    // detect_unchecked_vars addition
    global $CFG;
    if (!empty($CFG->detect_unchecked_vars)) {
        global $UNCHECKED_VARS;
        unset ($UNCHECKED_VARS->vars[$varname]);
    }

    if (isset($_POST[$varname])) {       // POST has precedence
        $param = $_POST[$varname];
    } else if (isset($_GET[$varname])) {
        $param = $_GET[$varname];
    } else {
        return $default;
    }

    return clean_param($param, $options);
}


/**
 * Used by {@link optional_param()} and {@link required_param()} to
 * clean the variables and/or cast to specific types, based on
 * an options field.
 * <code>
 * $course->format = clean_param($course->format, PARAM_ALPHA);
 * $selectedgrade_item = clean_param($selectedgrade_item, PARAM_CLEAN);
 * </code>
 *
 * @uses $CFG
 * @uses PARAM_CLEAN
 * @uses PARAM_INT
 * @uses PARAM_INTEGER
 * @uses PARAM_ALPHA
 * @uses PARAM_ALPHANUM
 * @uses PARAM_NOTAGS
 * @uses PARAM_ALPHATEXT
 * @uses PARAM_BOOL
 * @uses PARAM_SAFEDIR
 * @uses PARAM_CLEANFILE
 * @uses PARAM_FILE
 * @uses PARAM_PATH
 * @uses PARAM_HOST
 * @uses PARAM_URL
 * @uses PARAM_LOCALURL
 * @uses PARAM_CLEANHTML
 * @param mixed $param the variable we are cleaning
 * @param int $options a bit field that specifies the cleaning needed. This field is specified by combining PARAM_* definitions together with a logical or.
 * @return mixed
 */
function clean_param($param, $options) {

    global $CFG;

    if (is_array($param)) {              // Let's loop
        $newparam = array();
        foreach ($param as $key => $value) {
            $newparam[$key] = clean_param($value, $options);
        }
        return $newparam;
    }

    if (!$options) {
        return $param;                   // Return raw value
    }

    //this corrupts data - Sven
    //if ((string)$param == (string)(int)$param) {  // It's just an integer
    //    return (int)$param;
    //}

    if ($options & PARAM_CLEAN) {
// this breaks backslashes in user input
//        $param = stripslashes($param);   // Needed by kses to work fine
        $param = clean_text($param);     // Sweep for scripts, etc
// and this unnecessarily escapes quotes, etc in user input
//        $param = addslashes($param);     // Restore original request parameter slashes
    }

    if ($options & PARAM_INT) {
        $param = (int)$param;            // Convert to integer
    }

    if ($options & PARAM_ALPHA) {        // Remove everything not a-z
        $param = eregi_replace('[^a-zA-Z]', '', $param);
    }

    if ($options & PARAM_ALPHANUM) {     // Remove everything not a-zA-Z0-9
        $param = eregi_replace('[^A-Za-z0-9]', '', $param);
    }

    if ($options & PARAM_ALPHAEXT) {     // Remove everything not a-zA-Z/_-
        $param = eregi_replace('[^a-zA-Z/_-]', '', $param);
    }

    if ($options & PARAM_BOOL) {         // Convert to 1 or 0
        $tempstr = strtolower($param);
        if ($tempstr == 'on') {
            $param = 1;
        } else if ($tempstr == 'off') {
            $param = 0;
        } else {
            $param = empty($param) ? 0 : 1;
        }
    }

    if ($options & PARAM_NOTAGS) {       // Strip all tags completely
        $param = strip_tags($param);
    }

    if ($options & PARAM_SAFEDIR) {     // Remove everything not a-zA-Z0-9_-
        $param = eregi_replace('[^a-zA-Z0-9_-]', '', $param);
    }

    if ($options & PARAM_CLEANFILE) {    // allow only safe characters
        $param = clean_filename($param);
    }

    if ($options & PARAM_FILE) {         // Strip all suspicious characters from filename
        $param = ereg_replace('[[:cntrl:]]|[<>"`\|\':\\/]', '', $param);
        $param = ereg_replace('\.\.+', '', $param);
        if($param == '.') {
            $param = '';
        }
    }

    if ($options & PARAM_PATH) {         // Strip all suspicious characters from file path
        $param = str_replace('\\\'', '\'', $param);
        $param = str_replace('\\"', '"', $param);
        $param = str_replace('\\', '/', $param);
        $param = ereg_replace('[[:cntrl:]]|[<>"`\|\':]', '', $param);
        $param = ereg_replace('\.\.+', '', $param);
        $param = ereg_replace('//+', '/', $param);
        $param = ereg_replace('/(\./)+', '/', $param);
    }

    if ($options & PARAM_HOST) {         // allow FQDN or IPv4 dotted quad
        preg_replace('/[^\.\d\w-]/','', $param ); // only allowed chars
        // match ipv4 dotted quad
        if (preg_match('/(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})/',$param, $match)){
            // confirm values are ok
            if ( $match[0] > 255
                 || $match[1] > 255
                 || $match[3] > 255
                 || $match[4] > 255 ) {
                // hmmm, what kind of dotted quad is this?
                $param = '';
            }
        } elseif ( preg_match('/^[\w\d\.-]+$/', $param) // dots, hyphens, numbers
                   && !preg_match('/^[\.-]/',  $param) // no leading dots/hyphens
                   && !preg_match('/[\.-]$/',  $param) // no trailing dots/hyphens
                   ) {
            // all is ok - $param is respected
        } else {
            // all is not ok...
            $param='';
        }
    }

    if ($options & PARAM_URL) { // allow safe ftp, http, mailto urls

        include_once($CFG->dirroot . 'lib/validateurlsyntax.php');

        //
        // Parameters to validateurlsyntax()
        //
        // s? scheme is optional
        //   H? http optional
        //   S? https optional
        //   F? ftp   optional
        //   E? mailto optional
        // u- user section not allowed
        //   P- password not allowed
        // a? address optional
        //   I? Numeric IP address optional (can use IP or domain)
        //   p-  port not allowed -- restrict to default port
        // f? "file" path section optional
        //   q? query section optional
        //   r? fragment (anchor) optional
        //
        if (!empty($param) && validateUrlSyntax($param, 's?H?S?F?E?u-P-a?I?p-f?q?r?')) {
            // all is ok, param is respected
        } else {
            $param =''; // not really ok
        }
        $options ^= PARAM_URL; // Turn off the URL bit so that simple PARAM_URLs don't test true for PARAM_LOCALURL
    }

    if ($options & PARAM_LOCALURL) {
        // assume we passed the PARAM_URL test...
        // allow http absolute, root relative and relative URLs within wwwroot
        if (!empty($param)) {
            if (preg_match(':^/:', $param)) {
                // root-relative, ok!
            } elseif (preg_match('/^'.preg_quote($CFG->wwwroot, '/').'/i',$param)) {
                // absolute, and matches our wwwroot
            } else {
                // relative - let's make sure there are no tricks
                if (validateUrlSyntax($param, 's-u-P-a-p-f+q?r?')) {
                    // looks ok.
                } else {
                    $param = '';
                }
            }
        }
    }

    if ($options & PARAM_CLEANHTML) {
//        $param = stripslashes($param);         // Remove any slashes 
        $param = clean_text($param);           // Sweep for scripts, etc
//        $param = trim($param);                 // Sweep for scripts, etc
    }

    return $param;
}

/**
 * Retrieves the list of plugins available in the $plugin
 * directory. Defaults to 'mod'.
 *
 * NOTE: To get the list of enabled modules, do
 * get_records('modules', 'enabled', true) instead.
 *
 * @return array
 **/
function get_list_of_plugins($plugin='mod', $exclude='') {
    
    global $CFG;
    static $plugincache = array();
    $plugincachename = $plugin . "_" . $exclude;
    
    if (isset($plugincache[$plugincachename])) {
        $plugins = $plugincache[$plugincachename];
    } else {
        $plugins = array();
        $basedir = opendir($CFG->dirroot . $plugin);
        while (false !== ($dir = readdir($basedir))) {
            $firstchar = substr($dir, 0, 1);
            if ($firstchar == '.' or $dir == 'CVS' or $dir == '_vti_cnf' or $dir == $exclude) {
                continue;
            }
            if (filetype($CFG->dirroot . $plugin .'/'. $dir) != 'dir') {
                continue;
            }
            $plugins[] = $dir;
        }
        if ($plugins) {
            asort($plugins);
        }
        $plugincache[$plugincachename] = $plugins;
    }
    return $plugins;
}

// Adds a function to the variables used to cycle through plugin extensions
// to actions on objects
function listen_for_event($object_type, $event, $function) {
    
    global $CFG;
    $CFG->event_hooks[$object_type][$event][] = $function;
    
}

function plugin_hook($object_type,$event,$object = null) {
    
    global $CFG;
    
    if (!empty($CFG->event_hooks['all']['all']) && is_array($CFG->event_hooks['all']['all'])) {
        foreach($CFG->event_hooks['all']['all'] as $hook) {
            $object = $hook($object_type,$event,$object);
        }
    }
    if (!empty($CFG->event_hooks[$object_type]['all']) && is_array($CFG->event_hooks[$object_type]['all'])) {
        foreach($CFG->event_hooks[$object_type]['all'] as $hook) {
            $object = $hook($object_type,$event,$object);
        }
    }
    if (!empty($CFG->event_hooks['all'][$event]) && is_array($CFG->event_hooks['all'][$event])) {
        foreach($CFG->event_hooks['all'][$event] as $hook) {
            $object = $hook($object_type,$event,$object);
        }
    }
    if (!empty($CFG->event_hooks[$object_type][$event]) && is_array($CFG->event_hooks[$object_type][$event])) {
        foreach($CFG->event_hooks[$object_type][$event] as $hook) {
            $object = $hook($object_type,$event,$object);
        }
    }
    
    return $object;
    
}

function report_session_error() {
    global $CFG, $FULLME;
    if (empty($CFG->lang)) {
        $CFG->lang = "en";
    }

    //clear session cookies
    setcookie('ElggSession'.$CFG->sessioncookie, '', time() - 3600, $CFG->cookiepath);
    setcookie('ElggSessionTest'.$CFG->sessioncookie, '', time() - 3600, $CFG->cookiepath);
    //increment database error counters
    //if (isset($CFG->session_error_counter)) {
    //    set_config('session_error_counter', 1 + $CFG->session_error_counter);
    //} else {
    //    set_config('session_error_counter', 1);
    //}
    //called from setup.php, so gettext module hasn't been loaded yet
    redirect($FULLME, 'A server error that affects your login session was detected. Please login again or restart your browser.', 5);
}

/**
 * For security purposes, this function will check that the currently
 * given sesskey (passed as a parameter to the script or this function)
 * matches that of the current user.
 *
 * @param string $sesskey optionally provided sesskey
 * @return bool
 */
function confirm_sesskey($sesskey=NULL) {
    global $USER;

    if (!empty($USER->ignoresesskey) || !empty($CFG->ignoresesskey)) {
        return true;
    }

    if (empty($sesskey)) {
        $sesskey = required_param('sesskey');  // Check script parameters
    }

    if (!isset($USER->sesskey)) {
        return false;
    }

    return ($USER->sesskey === $sesskey);
}


/**
 * Makes sure that $USER->sesskey exists, if $USER itself exists. It sets a new sesskey
 * if one does not already exist, but does not overwrite existing sesskeys. Returns the
 * sesskey string if $USER exists, or boolean false if not.
 *
 * @uses $USER
 * @return string
 */
function sesskey() {
    global $USER;

    if(!isset($USER)) {
        return false;
    }

    if (empty($USER->sesskey)) {
        $USER->sesskey = random_string(10);
    }

    return $USER->sesskey;
}


/**
 * Send an email to a specified user
 *
 * @uses $CFG
 * @param user $user  A {@link $USER} object
 * @param user $from A {@link $USER} object
 * @param string $subject plain text subject line of the email
 * @param string $messagetext plain text version of the message
 * @param string $messagehtml complete html version of the message (optional)
 * @param string $attachment a file on the filesystem
 * @param string $attachname the name of the file (extension indicates MIME)
 * @param bool $usetrueaddress determines whether $from email address should
 *          be sent out. Will be overruled by user profile setting for maildisplay
 * @return bool|string Returns "true" if mail was sent OK, "emailstop" if email
 *          was blocked by user and "false" if there was another sort of error.
 */
function email_to_user($user, $from, $subject, $messagetext, $messagehtml='', $attachment='', $attachname='', $usetrueaddress=true, $repyto='', $replytoname='') {

    global $CFG;
    $textlib = textlib_get_instance();

    include_once($CFG->libdir .'/phpmailer/class.phpmailer.php');

    if (empty($user)) {
        return false;
    }

    /*
    if (over_bounce_threshold($user)) {
        error_log("User $user->id (".fullname($user).") is over bounce threshold! Not sending.");
        return false;
    }
    */ // this doesn't exist right now, we may bring it in later though.

    $mail = new phpmailer;

    $mail->Version = 'Elgg ';           // mailer version (should have $CFG->version on here but we don't have it yet)
    $mail->PluginDir = $CFG->libdir .'/phpmailer/';      // plugin directory (eg smtp plugin)


    $mail->CharSet = 'UTF-8'; // everything is now uft8

    if (empty($CFG->smtphosts)) {
        $mail->IsMail();                               // use PHP mail() = sendmail
    } else if ($CFG->smtphosts == 'qmail') {
        $mail->IsQmail();                              // use Qmail system
    } else {
        $mail->IsSMTP();                               // use SMTP directly
        if ($CFG->debug > 7) {
            echo '<pre>' . "\n";
            $mail->SMTPDebug = true;
        }
        $mail->Host = $CFG->smtphosts;               // specify main and backup servers

        if ($CFG->smtpuser) {                          // Use SMTP authentication
            $mail->SMTPAuth = true;
            $mail->Username = $CFG->smtpuser;
            $mail->Password = $CFG->smtppass;
        }
    }

    /* not here yet, leave it in just in case.
    // make up an email address for handling bounces
    if (!empty($CFG->handlebounces)) {
        $modargs = 'B'.base64_encode(pack('V',$user->ident)).substr(md5($user->email),0,16);
        $mail->Sender = generate_email_processing_address(0,$modargs);
    }
    else {
        $mail->Sender   =  $CFG->sysadminemail;
    }
    */
    $mail->Sender = $CFG->sysadminemail; // for elgg. delete if we change the above.

    // TODO add a preference for maildisplay
    if (is_string($from)) { // So we can pass whatever we want if there is need
        $mail->From     = $CFG->noreplyaddress;
        $mail->FromName = $from;
    } else if (empty($from)) { // make stuff up
        $mail->From     = $CFG->sysadminemail;
        $mail->FromName = $CFG->sitename.' '.__gettext('Administrator');
    } else if ($usetrueaddress and !empty($from->maildisplay)) {
        $mail->From     = $from->email;
        $mail->FromName = $from->name;
    } else {
        $mail->From     = $CFG->noreplyaddress;
        $mail->FromName = $from->name;
        if (empty($replyto)) {
            $mail->AddReplyTo($CFG->noreplyaddress,__gettext('Do not reply'));
        }
    }

    if (!empty($replyto)) {
        $mail->AddReplyTo($replyto,$replytoname);
    }

    $mail->Subject = $textlib->substr(stripslashes($subject), 0, 900);

    $mail->AddAddress($user->email, $user->name);

    $mail->WordWrap = 79;                               // set word wrap

    if (!empty($from->customheaders)) {                 // Add custom headers
        if (is_array($from->customheaders)) {
            foreach ($from->customheaders as $customheader) {
                $mail->AddCustomHeader($customheader);
            }
        } else {
            $mail->AddCustomHeader($from->customheaders);
        }
    }

    if (!empty($from->priority)) {
        $mail->Priority = $from->priority;
    }

    //TODO add a user preference for this. right now just send plaintext
    $user->mailformat = 0;
    if ($messagehtml && $user->mailformat == 1) { // Don't ever send HTML to users who don't want it
        $mail->IsHTML(true);
        $mail->Encoding = 'quoted-printable';           // Encoding to use
        $mail->Body    =  $messagehtml;
        $mail->AltBody =  "\n$messagetext\n";
    } else {
        $mail->IsHTML(false);
        $mail->Body =  "\n$messagetext\n";
    }

    if ($attachment && $attachname) {
        if (ereg( "\\.\\." ,$attachment )) {    // Security check for ".." in dir path
            $mail->AddAddress($CFG->sysadminemail,$CFG->sitename.' '.__gettext('Administrator'));
            $mail->AddStringAttachment('Error in attachment.  User attempted to attach a filename with a unsafe name.', 'error.txt', '8bit', 'text/plain');
        } else {
            require_once($CFG->libdir.'/filelib.php');
            $mimetype = mimeinfo('type', $attachname);
            $mail->AddAttachment($attachment, $attachname, 'base64', $mimetype);
        }
    }

    if ($mail->Send()) {
        //        set_send_count($user); // later
        return true;
    } else {
        mtrace('ERROR: '. $mail->ErrorInfo);
        return false;
    }
}

/**
 * Returns an array with all the filenames in
 * all subdirectories, relative to the given rootdir.
 * If excludefile is defined, then that file/directory is ignored
 * If getdirs is true, then (sub)directories are included in the output
 * If getfiles is true, then files are included in the output
 * (at least one of these must be true!)
 *
 * @param string $rootdir  ?
 * @param string $excludefile  If defined then the specified file/directory is ignored
 * @param bool $descend  ?
 * @param bool $getdirs  If true then (sub)directories are included in the output
 * @param bool $getfiles  If true then files are included in the output
 * @return array An array with all the filenames in
 * all subdirectories, relative to the given rootdir
 * @todo Finish documenting this function. Add examples of $excludefile usage.
 */
function get_directory_list($rootdir, $excludefile='', $descend=true, $getdirs=false, $getfiles=true) {

    $dirs = array();

    if (!$getdirs and !$getfiles) {   // Nothing to show
        return $dirs;
    }

    if (!is_dir($rootdir)) {          // Must be a directory
        return $dirs;
    }

    if (!$dir = opendir($rootdir)) {  // Can't open it for some reason
        return $dirs;
    }

    while (false !== ($file = readdir($dir))) {
        $firstchar = substr($file, 0, 1);
        if ($firstchar == '.' or $file == 'CVS' or $file == $excludefile) {
            continue;
        }
        $fullfile = $rootdir .'/'. $file;
        if (filetype($fullfile) == 'dir') {
            if ($getdirs) {
                $dirs[] = $file;
            }
            if ($descend) {
                $subdirs = get_directory_list($fullfile, $excludefile, $descend, $getdirs, $getfiles);
                foreach ($subdirs as $subdir) {
                    $dirs[] = $file .'/'. $subdir;
                }
            }
        } else if ($getfiles) {
            $dirs[] = $file;
        }
    }
    closedir($dir);

    asort($dirs);

    return $dirs;
}

/**
 * handy function to loop through an array of files and resolve any filename conflicts
 * both in the array of filenames and for what is already on disk.
 */

function resolve_filename_collisions($destination,$files,$format='%s_%d.%s') {
    foreach ($files as $k => $f) {
        if (check_potential_filename($destination,$f,$files)) {
            $bits = explode('.', $f);
            for ($i = 1; true; $i++) {
                $try = sprintf($format, $bits[0], $i, $bits[1]);
                if (!check_potential_filename($destination,$try,$files)) {
                    $files[$k] = $try;
                    break;
                }
            }
        }
    }
    return $files;
}

/**
 * @used by resolve_filename_collisions
 */
function check_potential_filename($destination,$filename,$files) {
    if (file_exists($destination.'/'.$filename)) {
        return true;
    }
    if (count(array_keys($files,$filename)) > 1) {
        return true;
    }
    return false;
}

/**
 * Adds up all the files in a directory and works out the size.
 *
 * @param string $rootdir  ?
 * @param string $excludefile  ?
 * @return array
 * @todo Finish documenting this function
 */
function get_directory_size($rootdir, $excludefile='') {

    global $CFG;
    $textlib = textlib_get_instance();

    // do it this way if we can, it's much faster
    if (!empty($CFG->pathtodu) && is_executable(trim($CFG->pathtodu))) {
        $command = trim($CFG->pathtodu).' -sk --apparent-size '.escapeshellarg($rootdir);
        exec($command,$output,$return);
        if (is_array($output)) {
            return get_real_size(intval($output[0]).'k'); // we told it to return k.
        }
    }
    
    $size = 0;

    if (!is_dir($rootdir)) {          // Must be a directory
        return $dirs;
    }

    if (!$dir = @opendir($rootdir)) {  // Can't open it for some reason
        return $dirs;
    }

    while (false !== ($file = readdir($dir))) {
        $firstchar = $textlib->substr($file, 0, 1);
        if ($firstchar == '.' or $file == 'CVS' or $file == $excludefile) {
            continue;
        }
        $fullfile = $rootdir .'/'. $file;
        if (filetype($fullfile) == 'dir') {
            $size += get_directory_size($fullfile, $excludefile);
        } else {
            $size += filesize($fullfile);
        }
    }
    closedir($dir);

    return $size;
}

/**
 * Converts numbers like 10M into bytes.
 *
 * @param mixed $size The size to be converted
 * @return mixed
 */
function get_real_size($size=0) {
    if (!$size) {
        return 0;
    }
    $scan['MB'] = 1048576;
    $scan['Mb'] = 1048576;
    $scan['M'] = 1048576;
    $scan['m'] = 1048576;
    $scan['KB'] = 1024;
    $scan['Kb'] = 1024;
    $scan['K'] = 1024;
    $scan['k'] = 1024;

    while (list($key) = each($scan)) {
        if ((strlen($size)>strlen($key))&&(substr($size, strlen($size) - strlen($key))==$key)) {
            $size = substr($size, 0, strlen($size) - strlen($key)) * $scan[$key];
            break;
        }
    }
    return $size;
}

/**
 * Converts bytes into display form
 *
 * @param string $size  ?
 * @return string
 * @staticvar string $gb Localized string for size in gigabytes
 * @staticvar string $mb Localized string for size in megabytes
 * @staticvar string $kb Localized string for size in kilobytes
 * @staticvar string $b Localized string for size in bytes
 * @todo Finish documenting this function. Verify return type.
 */
function display_size($size) {

    static $gb, $mb, $kb, $b;

    if (empty($gb)) {
        $gb = __gettext('GB');
        $mb = __gettext('MB');
        $kb = __gettext('KB');
        $b  = __gettext('bytes');
    }

    if ($size >= 1073741824) {
        $size = round($size / 1073741824 * 10) / 10 . $gb;
    } else if ($size >= 1048576) {
        $size = round($size / 1048576 * 10) / 10 . $mb;
    } else if ($size >= 1024) {
        $size = round($size / 1024 * 10) / 10 . $kb;
    } else {
        $size = $size .' '. $b;
    }
    return $size;
}

/*
 * Convert high ascii characters into low ascii
 * This code is from http://kalsey.com/2004/07/dirify_in_php/
 *
 */
function convert_high_ascii($s) {
    $HighASCII = array(
        "!\xc0!" => 'A',    # A`
        "!\xe0!" => 'a',    # a`
        "!\xc1!" => 'A',    # A'
        "!\xe1!" => 'a',    # a'
        "!\xc2!" => 'A',    # A^
        "!\xe2!" => 'a',    # a^
        "!\xc4!" => 'Ae',   # A:
        "!\xe4!" => 'ae',   # a:
        "!\xc3!" => 'A',    # A~
        "!\xe3!" => 'a',    # a~
        "!\xc8!" => 'E',    # E`
        "!\xe8!" => 'e',    # e`
        "!\xc9!" => 'E',    # E'
        "!\xe9!" => 'e',    # e'
        "!\xca!" => 'E',    # E^
        "!\xea!" => 'e',    # e^
        "!\xcb!" => 'Ee',   # E:
        "!\xeb!" => 'ee',   # e:
        "!\xcc!" => 'I',    # I`
        "!\xec!" => 'i',    # i`
        "!\xcd!" => 'I',    # I'
        "!\xed!" => 'i',    # i'
        "!\xce!" => 'I',    # I^
        "!\xee!" => 'i',    # i^
        "!\xcf!" => 'Ie',   # I:
        "!\xef!" => 'ie',   # i:
        "!\xd2!" => 'O',    # O`
        "!\xf2!" => 'o',    # o`
        "!\xd3!" => 'O',    # O'
        "!\xf3!" => 'o',    # o'
        "!\xd4!" => 'O',    # O^
        "!\xf4!" => 'o',    # o^
        "!\xd6!" => 'Oe',   # O:
        "!\xf6!" => 'oe',   # o:
        "!\xd5!" => 'O',    # O~
        "!\xf5!" => 'o',    # o~
        "!\xd8!" => 'Oe',   # O/
        "!\xf8!" => 'oe',   # o/
        "!\xd9!" => 'U',    # U`
        "!\xf9!" => 'u',    # u`
        "!\xda!" => 'U',    # U'
        "!\xfa!" => 'u',    # u'
        "!\xdb!" => 'U',    # U^
        "!\xfb!" => 'u',    # u^
        "!\xdc!" => 'Ue',   # U:
        "!\xfc!" => 'ue',   # u:
        "!\xc7!" => 'C',    # ,C
        "!\xe7!" => 'c',    # ,c
        "!\xd1!" => 'N',    # N~
        "!\xf1!" => 'n',    # n~
        "!\xdf!" => 'ss'
    );
    $find = array_keys($HighASCII);
    $replace = array_values($HighASCII);
    $s = preg_replace($find,$replace,$s);
    return $s;
}

/*
 * Cleans a given filename by removing suspicious or troublesome characters
 * Only these are allowed:
 *    alphanumeric _ - .
 *
 * @param string $string  ?
 * @return string
 */
function clean_filename($string) {
    $string = convert_high_ascii($string);
    $string = eregi_replace("\.\.+", '', $string);
    $string = preg_replace('/[^\.a-zA-Z\d\_-]/','_', $string ); // only allowed chars
    $string = eregi_replace("_+", '_', $string);
    return $string;
}



/**
 * Function to raise the memory limit to a new value.
 * Will respect the memory limit if it is higher, thus allowing
 * settings in php.ini, apache conf or command line switches
 * to override it
 *
 * The memory limit should be expressed with a string (eg:'64M')
 *
 * @param string $newlimit the new memory limit
 * @return bool
 */
function raise_memory_limit ($newlimit) {

    if (empty($newlimit)) {
        return false;
    }

    $cur = @ini_get('memory_limit');
    if (empty($cur)) {
        // if php is compiled without --enable-memory-limits
        // apparently memory_limit is set to ''
        $cur=0;
    } else {
        if ($cur == -1){
            return true; // unlimited mem!
        }
      $cur = get_real_size($cur);
    }

    $new = get_real_size($newlimit);
    if ($new > $cur) {
        ini_set('memory_limit', $newlimit);
        return true;
    }
    return false;
}

/**
 * Converts string to lowercase using most compatible function available.
 *
 * @param string $string The string to convert to all lowercase characters.
 * @param string $encoding The encoding on the string.
 * @return string
 * @todo Add examples of calling this function with/without encoding types
 */
function elgg_strtolower ($string, $encoding='') {
    $textlib = textlib_get_instance();
    return $textlib->strtolower($string, $encoding?$encoding:'utf-8');
}



/**
 * Given a simple array, this shuffles it up just like shuffle()
 * Unlike PHP's shuffle() ihis function works on any machine.
 *
 * @param array $array The array to be rearranged
 * @return array
 */
function swapshuffle($array) {

    srand ((double) microtime() * 10000000);
    $last = count($array) - 1;
    for ($i=0;$i<=$last;$i++) {
        $from = rand(0,$last);
        $curr = $array[$i];
        $array[$i] = $array[$from];
        $array[$from] = $curr;
    }
    return $array;
}

/**
 * Like {@link swapshuffle()}, but works on associative arrays
 *
 * @param array $array The associative array to be rearranged
 * @return array
 */
function swapshuffle_assoc($array) {
///

    $newkeys = swapshuffle(array_keys($array));
    foreach ($newkeys as $newkey) {
        $newarray[$newkey] = $array[$newkey];
    }
    return $newarray;
}

/**
 * Given an arbitrary array, and a number of draws,
 * this function returns an array with that amount
 * of items.  The indexes are retained.
 *
 * @param array $array ?
 * @param ? $draws ?
 * @return ?
 * @todo Finish documenting this function
 */
function draw_rand_array($array, $draws) {
    srand ((double) microtime() * 10000000);

    $return = array();

    $last = count($array);

    if ($draws > $last) {
        $draws = $last;
    }

    while ($draws > 0) {
        $last--;

        $keys = array_keys($array);
        $rand = rand(0, $last);

        $return[$keys[$rand]] = $array[$keys[$rand]];
        unset($array[$keys[$rand]]);

        $draws--;
    }

    return $return;
}


/**
 * Function to check the passed address is within the passed subnet
 *
 * The parameter is a comma separated string of subnet definitions.
 * Subnet strings can be in one of two formats:
 *   1: xxx.xxx.xxx.xxx/xx
 *   2: xxx.xxx
 * Code for type 1 modified from user posted comments by mediator at
 * {@link http://au.php.net/manual/en/function.ip2long.php}
 *
 * @param string $addr    The address you are checking
 * @param string $subnetstr    The string of subnet addresses
 * @return bool
 */
function address_in_subnet($addr, $subnetstr) {

    $subnets = explode(',', $subnetstr);
    $found = false;
    $addr = trim($addr);

    foreach ($subnets as $subnet) {
        $subnet = trim($subnet);
        if (strpos($subnet, '/') !== false) { /// type 1

            list($ip, $mask) = explode('/', $subnet);
            $mask = 0xffffffff << (32 - $mask);
            $found = ((ip2long($addr) & $mask) == (ip2long($ip) & $mask));

        } else { /// type 2
            $found = (strpos($addr, $subnet) === 0);
        }

        if ($found) {
            break;
        }
    }

    return $found;
}

/**
 * For outputting debugging info
 *
 * @uses STDOUT
 * @param string $string ?
 * @param string $eol ?
 * @todo Finish documenting this function
 */
function mtrace($string, $eol="\n", $sleep=0) {

    if (defined('STDOUT')) {
        fwrite(STDOUT, $string.$eol);
    } else {
        echo $string . $eol;
    }

    flush();

    //delay to keep message on user's screen in case of subsequent redirect
    if ($sleep) {
        sleep($sleep);
    }
}

//Replace 1 or more slashes or backslashes to 1 slash
function cleardoubleslashes ($path) {
    return preg_replace('/(\/|\\\){1,}/','/',$path);
}



/**
 * Returns most reliable client address
 *
 * @return string The remote IP address
 */
 function getremoteaddr() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return cleanremoteaddr($_SERVER['HTTP_CLIENT_IP']);
    }
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return cleanremoteaddr($_SERVER['HTTP_X_FORWARDED_FOR']);
    }
    if (!empty($_SERVER['REMOTE_ADDR'])) {
        return cleanremoteaddr($_SERVER['REMOTE_ADDR']);
    }
    return '';
}

/** 
 * Cleans a remote address ready to put into the log table
 */
function cleanremoteaddr($addr) {
    $originaladdr = $addr;
    $matches = array();
    // first get all things that look like IP addresses.
    if (!preg_match_all('/(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})/',$addr,$matches,PREG_SET_ORDER)) {
        return '';
    }
    $goodmatches = array();
    $lanmatches = array();
    foreach ($matches as $match) {
        //        print_r($match);
        // check to make sure it's not an internal address.
        // the following are reserved for private lans...
        // 10.0.0.0 - 10.255.255.255
        // 172.16.0.0 - 172.31.255.255
        // 192.168.0.0 - 192.168.255.255
        // 169.254.0.0 -169.254.255.255 
        $bits = explode('.',$match[0]);
        if (count($bits) != 4) {
            // weird, preg match shouldn't give us it.
            continue;
        }
        if (($bits[0] == 10) 
            || ($bits[0] == 172 && $bits[1] >= 16 && $bits[1] <= 31)
            || ($bits[0] == 192 && $bits[1] == 168)
            || ($bits[0] == 169 && $bits[1] == 254)) {
            $lanmatches[] = $match[0];
            continue;
        }
        // finally, it's ok
        $goodmatches[] = $match[0];
    }
    if (!count($goodmatches)) {
        // perhaps we have a lan match, it's probably better to return that.
        if (!count($lanmatches)) {
            return '';
        } else {
            return array_pop($lanmatches);
        }
    } 
    if (count($goodmatches) == 1) {
        return $goodmatches[0];
    }
    error_log("NOTICE: cleanremoteaddr gives us something funny: $originaladdr had ".count($goodmatches)." matches");
    // we need to return something, so
    return array_pop($goodmatches);
}

/**
 * html_entity_decode is only supported by php 4.3.0 and higher
 * so if it is not predefined, define it here
 *
 * @param string $string ?
 * @return string
 * @todo Finish documenting this function
 */
if(!function_exists('html_entity_decode')) {
     function html_entity_decode($string, $quote_style = ENT_COMPAT, $charset = 'ISO-8859-1') {
        $trans_tbl = get_html_translation_table(HTML_ENTITIES, $quote_style);
        $trans_tbl = array_flip($trans_tbl);
        return strtr($string, $trans_tbl);
    }
}

/**
 * The clone keyword is only supported from PHP 5 onwards.
 * The behaviour of $obj2 = $obj1 differs fundamentally
 * between PHP 4 and PHP 5. In PHP 4 a copy of $obj1 was
 * created, in PHP 5 $obj1 is referenced. To create a copy
 * in PHP 5 the clone keyword was introduced. This function
 * simulates this behaviour for PHP < 5.0.0.
 * See also: http://mjtsai.com/blog/2004/07/15/php-5-object-references/
 *
 * Modified 2005-09-29 by Eloy (from Julian Sedding proposal)
 * Found a better implementation (more checks and possibilities) from PEAR:
 * http://cvs.php.net/co.php/pear/PHP_Compat/Compat/Function/clone.php
 * 
 * @param object $obj
 * @return object
 */
if(!check_php_version('5.0.0')) {
// the eval is needed to prevent PHP 5 from getting a parse error!
eval('
    function clone($obj) {
    /// Sanity check
        if (!is_object($obj)) {
            user_error(\'clone() __clone method called on non-object\', E_USER_WARNING);
            return;
        }

    /// Use serialize/unserialize trick to deep copy the object
        $obj = unserialize(serialize($obj));

    /// If there is a __clone method call it on the "new" class
        if (method_exists($obj, \'__clone\')) {
            $obj->__clone();
        }

        return $obj;
    }
');
}


/**
 * microtime_diff
 *
 * @param string $a ?
 * @param string $b ?
 * @return string
 * @todo Finish documenting this function
 */
function microtime_diff($a, $b) {
    list($a_dec, $a_sec) = explode(' ', $a);
    list($b_dec, $b_sec) = explode(' ', $b);
    return $b_sec - $a_sec + $b_dec - $a_dec;
}


/**
 *** get_performance_info() pairs up with init_performance_info()
 *** loaded in setup.php. Returns an array with 'html' and 'txt' 
 *** values ready for use, and each of the individual stats provided
 *** separately as well.
 ***
 **/
function get_performance_info() {
    global $CFG, $PERF;

    $info = array();
    $info['html'] = '';         // holds userfriendly HTML representation
    $info['txt']  = me() . ' '; // holds log-friendly representation

    $info['realtime'] = microtime_diff($PERF->starttime, microtime());
     
    $info['html'] .= '<span class="timeused">'.$info['realtime'].' secs</span> ';
    $info['txt'] .= 'time: '.$info['realtime'].'s ';

    if (function_exists('memory_get_usage')) {
        $info['memory_total'] = memory_get_usage();
        $info['memory_growth'] = memory_get_usage() - $PERF->startmemory;
        $info['html'] .= '<span class="memoryused">RAM: '.display_size($info['memory_total']).'</span> ';
        $info['txt']  .= 'memory_total: '.$info['memory_total'].'B (' . display_size($info['memory_total']).') memory_growth: '.$info['memory_growth'].'B ('.display_size($info['memory_growth']).') ';
    }

    $inc = get_included_files();
    //error_log(print_r($inc,1));
    $info['includecount'] = count($inc);
    $info['html'] .= '<span class="included">Included '.$info['includecount'].' files</span> ';
    $info['txt']  .= 'includecount: '.$info['includecount'].' ';

    if (!empty($PERF->dbqueries)) {
        $info['dbqueries'] = $PERF->dbqueries;
        $info['html'] .= '<span class="dbqueries">DB queries '.$info['dbqueries'].'</span> ';
        $info['txt'] .= 'dbqueries: '.$info['dbqueries'].' ';
    }

    if (!empty($PERF->logwrites)) {
        $info['logwrites'] = $PERF->logwrites;
        $info['html'] .= '<span class="logwrites">Log writes '.$info['logwrites'].'</span> ';
        $info['txt'] .= 'logwrites: '.$info['logwrites'].' ';
    }

    if (function_exists('posix_times')) {
        $ptimes = posix_times();
        foreach ($ptimes as $key => $val) {
            $info[$key] = $ptimes[$key] -  $PERF->startposixtimes[$key];            
        }
        $info['html'] .= "<span class=\"posixtimes\">ticks: $info[ticks] user: $info[utime] sys: $info[stime] cuser: $info[cutime] csys: $info[cstime]</span> ";
        $info['txt'] .= "ticks: $info[ticks] user: $info[utime] sys: $info[stime] cuser: $info[cutime] csys: $info[cstime] ";

    }

    // Grab the load average for the last minute
    // /proc will only work under some linux configurations
    // while uptime is there under MacOSX/Darwin and other unices
    if (is_readable('/proc/loadavg') && $loadavg = @file('/proc/loadavg')) {
        list($server_load) = explode(' ', $loadavg[0]);
        unset($loadavg);
    } else if ( function_exists('is_executable') && is_executable('/usr/bin/uptime') && $loadavg = `/usr/bin/uptime` ) {
        if (preg_match('/load averages?: (\d+[\.:]\d+)/', $loadavg, $matches)) {
            $server_load = $matches[1];
        } else {
            trigger_error('Could not parse uptime output!');
        }
    }
    if (!empty($server_load)) {
        $info['serverload'] = $server_load;
        $info['html'] .= '<span class="serverload">Load average: '.$info['serverload'].'</span> ';
        $info['txt'] .= 'serverload: '.$info['serverload'];
    }
    

    $info['html'] = '<div class="performanceinfo">'.$info['html'].'</div>';
    return $info;
}

if (!function_exists('file_get_contents')) {
   function file_get_contents($file) {
       $file = file($file);
       return $file ? implode('', $file) : false;
   }
}




/**
 * Detect if an object or a class contains a given property
 * will take an actual object or the name of a class
 * @param mix $obj Name of class or real object to test
 * @param string $property name of property to find
 * @return bool true if property exists
 */
function object_property_exists( $obj, $property ) {
    if (is_string( $obj )) {
        $properties = get_class_vars( $obj );
    }
    else {
        $properties = get_object_vars( $obj );
    }
    return array_key_exists( $property, $properties );
}


/**
 * Add quotes to HTML characters
 *
 * Returns $var with HTML characters (like "<", ">", etc.) properly quoted.
 * This function is very similar to {@link p()}
 *
 * @param string $var the string potentially containing HTML characters
 * @return string
 */
function s($var) {
    if ($var == '0') {  // for integer 0, boolean false, string '0'
        return '0';
    }
    return preg_replace("/&amp;(#\d+);/iu", '&$1;', htmlspecialchars(stripslashes_safe($var), ENT_COMPAT, 'utf-8'));
}

/**
 * Add quotes to HTML characters
 *
 * Prints $var with HTML characters (like "<", ">", etc.) properly quoted.
 * This function is very similar to {@link s()}
 *
 * @param string $var the string potentially containing HTML characters
 * @return string
 */
function p($var) {
    echo s($var);
}


/**
 * Ensure that a variable is set
 *
 * Return $var if it is defined, otherwise return $default,
 * This function is very similar to {@link optional_variable()}
 *
 * @param    mixed $var the variable which may be unset
 * @param    mixed $default the value to return if $var is unset
 * @return   mixed
 */
function nvl(&$var, $default='') {

    return isset($var) ? $var : $default;
}

/**
 * Remove query string from url
 *
 * Takes in a URL and returns it without the querystring portion
 *
 * @param string $url the url which may have a query string attached
 * @return string
 */
 function strip_querystring($url) {
     $textlib = textlib_get_instance();

    if ($commapos = $textlib->strpos($url, '?')) {
        return $textlib->substr($url, 0, $commapos);
    } else {
        return $url;
    }
}

/**
 * Returns the URL of the HTTP_REFERER, less the querystring portion
 * @return string
 */
function get_referer() {

    return strip_querystring(nvl($_SERVER['HTTP_REFERER']));
}


/**
 * Returns the name of the current script, WITH the querystring portion.
 * this function is necessary because PHP_SELF and REQUEST_URI and SCRIPT_NAME
 * return different things depending on a lot of things like your OS, Web
 * server, and the way PHP is compiled (ie. as a CGI, module, ISAPI, etc.)
 * <b>NOTE:</b> This function returns false if the global variables needed are not set.
 *
 * @return string
 */
 function me() {

    if (!empty($_SERVER['REQUEST_URI'])) {
        return $_SERVER['REQUEST_URI'];

    } else if (!empty($_SERVER['PHP_SELF'])) {
        if (!empty($_SERVER['QUERY_STRING'])) {
            return $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING'];
        }
        return $_SERVER['PHP_SELF'];

    } else if (!empty($_SERVER['SCRIPT_NAME'])) {
        if (!empty($_SERVER['QUERY_STRING'])) {
            return $_SERVER['SCRIPT_NAME'] .'?'. $_SERVER['QUERY_STRING'];
        }
        return $_SERVER['SCRIPT_NAME'];

    } else if (!empty($_SERVER['URL'])) {     // May help IIS (not well tested)
        if (!empty($_SERVER['QUERY_STRING'])) {
            return $_SERVER['URL'] .'?'. $_SERVER['QUERY_STRING'];
        }
        return $_SERVER['URL'];

    } else {
        notify('Warning: Could not find any of these web server variables: $REQUEST_URI, $PHP_SELF, $SCRIPT_NAME or $URL');
        return false;
    }
}

/**
 * Like {@link me()} but returns a full URL
 * @see me()
 * @return string
 */
function qualified_me() {

    global $CFG;

    if (!empty($CFG->wwwroot)) {
        $url = parse_url($CFG->wwwroot);
    }

    if (!empty($url['host'])) {
        $hostname = $url['host'];
    } else if (!empty($_SERVER['SERVER_NAME'])) {
        $hostname = $_SERVER['SERVER_NAME'];
    } else if (!empty($_ENV['SERVER_NAME'])) {
        $hostname = $_ENV['SERVER_NAME'];
    } else if (!empty($_SERVER['HTTP_HOST'])) {
        $hostname = $_SERVER['HTTP_HOST'];
    } else if (!empty($_ENV['HTTP_HOST'])) {
        $hostname = $_ENV['HTTP_HOST'];
    } else {
        notify('Warning: could not find the name of this server!');
        return false;
    }

    if (!empty($url['port'])) {
        $hostname .= ':'.$url['port'];
    } else if (!empty($_SERVER['SERVER_PORT'])) {
        if ($_SERVER['SERVER_PORT'] != 80 && $_SERVER['SERVER_PORT'] != 443) {
            $hostname .= ':'.$_SERVER['SERVER_PORT'];
        }
    }

    if (isset($_SERVER['HTTPS'])) {
        $protocol = ($_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
    } else if (isset($_SERVER['SERVER_PORT'])) { # Apache2 does not export $_SERVER['HTTPS']
        $protocol = ($_SERVER['SERVER_PORT'] == '443') ? 'https://' : 'http://';
    } else {
        $protocol = 'http://';
    }

    $url_prefix = $protocol.$hostname;
    return $url_prefix . me();
}

/**
 * Determine if a web referer is valid
 *
 * Returns true if the referer is the same as the goodreferer. If
 * the referer to test is not specified, use {@link qualified_me()}.
 * If the admin has not set secure forms ($CFG->secureforms) then
 * this function returns true regardless of a match.
 *
 * @uses $CFG
 * @param string $goodreferer the url to compare to referer
 * @return boolean
 */
function match_referer($goodreferer = '') {
    global $CFG;

    if (empty($CFG->secureforms)) {    // Don't bother checking referer
        return true;
    }

    if ($goodreferer == 'nomatch') {   // Don't bother checking referer
        return true;
    }

    if (empty($goodreferer)) {
        $goodreferer = qualified_me();
    }

    $referer = get_referer();

    return (($referer == $goodreferer) or ($referer == $CFG->wwwroot) or ($referer == $CFG->wwwroot .'index.php'));
}

/**
 * Determine if there is data waiting to be processed from a form
 *
 * Used on most forms in Moodle to check for data
 * Returns the data as an object, if it's found.
 * This object can be used in foreach loops without
 * casting because it's cast to (array) automatically
 *
 * Checks that submitted POST data exists, and also
 * checks the referer against the given url (it uses
 * the current page if none was specified.
 *
 * @uses $CFG
 * @param string $url the url to compare to referer for secure forms
 * @return boolean
 */
function data_submitted($url='') {


    global $CFG;

    if (empty($_POST)) {
        return false;

    } else {
        if (match_referer($url)) {
            return (object)$_POST;
        } else {
            if ($CFG->debug > 10) {
                notice('The form did not come from this page! (referer = '. get_referer() .')');
            }
            return false;
        }
    }
}

/**
 * Moodle replacement for php stripslashes() function
 *
 * The standard php stripslashes() removes ALL backslashes
 * even from strings - so  C:\temp becomes C:temp - this isn't good.
 * This function should work as a fairly safe replacement
 * to be called on quoted AND unquoted strings (to be sure)
 *
 * @param string the string to remove unsafe slashes from
 * @return string
 */
function stripslashes_safe($string) {

    $string = str_replace("\\'", "'", $string);
    $string = str_replace('\\"', '"', $string);
    $string = str_replace('\\\\', '\\', $string);
    return $string;
}

/**
 * Recursive implementation of stripslashes()
 *
 * This function will allow you to strip the slashes from a variable.
 * If the variable is an array or object, slashes will be stripped
 * from the items (or properties) it contains, even if they are arrays
 * or objects themselves.
 *
 * @param mixed the variable to remove slashes from
 * @return mixed
 */
function stripslashes_recursive($var) {
    if(is_object($var)) {
        $properties = get_object_vars($var);
        foreach($properties as $property => $value) {
            $var->$property = stripslashes_recursive($value);
        }
    }
    else if(is_array($var)) {
        foreach($var as $property => $value) {
            $var[$property] = stripslashes_recursive($value);
        }
    }
    else if(is_string($var)) {
        $var = stripslashes($var);
    }
    return $var;
}

/**
 * This does a search and replace, ignoring case
 * This function is only used for versions of PHP older than version 5
 * which do not have a native version of this function.
 * Taken from the PHP manual, by bradhuizenga @ softhome.net
 *
 * @param string $find the string to search for
 * @param string $replace the string to replace $find with
 * @param string $string the string to search through
 * return string
 */
if (!function_exists('str_ireplace')) {    /// Only exists in PHP 5
    function str_ireplace($find, $replace, $string) {
        $textlib = textlib_get_instance();

        if (!is_array($find)) {
            $find = array($find);
        }

        if(!is_array($replace)) {
            if (!is_array($find)) {
                $replace = array($replace);
            } else {
                // this will duplicate the string into an array the size of $find
                $c = count($find);
                $rString = $replace;
                unset($replace);
                for ($i = 0; $i < $c; $i++) {
                    $replace[$i] = $rString;
                }
            }
        }

        foreach ($find as $fKey => $fItem) {
            $between = explode($textlib->strtolower($fItem),$textlib->strtolower($string));
            $pos = 0;
            foreach($between as $bKey => $bItem) {
                $between[$bKey] = $textlib->substr($string,$pos,$textlib->strlen($bItem));
                $pos += $textlib->strlen($bItem) + $textlib->strlen($fItem);
            }
            $string = implode($replace[$fKey],$between);
        }
        return ($string);
    }
}

/**
 * Locate the position of a string in another string
 *
 * This function is only used for versions of PHP older than version 5
 * which do not have a native version of this function.
 * Taken from the PHP manual, by dmarsh @ spscc.ctc.edu
 *
 * @param string $haystack The string to be searched
 * @param string $needle The string to search for
 * @param int $offset The position in $haystack where the search should begin.
 */
if (!function_exists('stripos')) {    /// Only exists in PHP 5
    function stripos($haystack, $needle, $offset=0) {
        $textlib = textlib_get_instance();

        return $textlib->strpos($textlib->strtoupper($haystack), $textlib->strtoupper($needle), $offset);
    }
}


/**
 * Returns true if the current version of PHP is greater that the specified one.
 *
 * @param string $version The version of php being tested.
 * @return boolean
 * @todo Finish documenting this function
 */
function check_php_version($version='4.1.0') {
    return (version_compare(phpversion(), $version) >= 0);
}


/**
 * Checks to see if is a browser matches the specified
 * brand and is equal or better version.
 *
 * @uses $_SERVER
 * @param string $brand The browser identifier being tested
 * @param int $version The version of the browser
 * @return boolean
 * @todo Finish documenting this function
 */
 function check_browser_version($brand='MSIE', $version=5.5) {
    $agent = $_SERVER['HTTP_USER_AGENT'];

    if (empty($agent)) {
        return false;
    }

    switch ($brand) {

      case 'Gecko':   /// Gecko based browsers

          if (substr_count($agent, 'Camino')) {
              // MacOS X Camino support
              $version = 20041110;
          }

          // the proper string - Gecko/CCYYMMDD Vendor/Version
          // Faster version and work-a-round No IDN problem.
          if (preg_match("/Gecko\/([0-9]+)/i", $agent, $match)) {
              if ($match[1] > $version) {
                      return true;
                  }
              }
          break;


      case 'MSIE':   /// Internet Explorer

          if (strpos($agent, 'Opera')) {     // Reject Opera
              return false;
          }
          $string = explode(';', $agent);
          if (!isset($string[1])) {
              return false;
          }
          $string = explode(' ', trim($string[1]));
          if (!isset($string[0]) and !isset($string[1])) {
              return false;
          }
          if ($string[0] == $brand and (float)$string[1] >= $version ) {
              return true;
          }
          break;

    }

    return false;
}


/**
 * Set a variable's value depending on whether or not it already has a value.
 *
 * If variable is set, set it to the set_value otherwise set it to the
 * unset_value.  used to handle checkboxes when you are expecting them from
 * a form
 *
 * @param mixed $var Passed in by reference. The variable to check.
 * @param mixed $set_value The value to set $var to if $var already has a value.
 * @param mixed $unset_value The value to set $var to if $var does not already have a value.
 */
function checked(&$var, $set_value = 1, $unset_value = 0) {

    if (empty($var)) {
        $var = $unset_value;
    } else {
        $var = $set_value;
    }
}

/**
 * Prints the word "checked" if a variable is true, otherwise prints nothing,
 * used for printing the word "checked" in a checkbox form element.
 *
 * @param boolean $var Variable to be checked for true value
 * @param string $true_value Value to be printed if $var is true
 * @param string $false_value Value to be printed if $var is false
 */
function frmchecked(&$var, $true_value = 'checked', $false_value = '') {

    if ($var) {
        echo $true_value;
    } else {
        echo $false_value;
    }
}

/**
 * Prints a simple button to close a window
 */
function close_window_button($name='closewindow') {

    echo '<div style="text-align: center;">' . "\n";
    echo '<script type="text/javascript">' . "\n";
    echo '<!--' . "\n";
    echo "document.write('<form>');\n";
    echo "document.write('<input type=\"button\" onclick=\"self.close();\" value=\"".__gettext("Close this window")."\" />');\n";
    echo "document.write('<\/form>');\n";
    echo '-->' . "\n";
    echo '</script>' . "\n";
    echo '<noscript>' . "\n";
    print_string($name);
    echo '</noscript>' . "\n";
    echo '</div>' . "\n";
}

/*
 * Try and close the current window immediately using Javascript
 */
function close_window($delay=0) {
    echo '<script language="JavaScript" type="text/javascript">'."\n";
    echo '<!--'."\n";
    if ($delay) {
        sleep($delay);
    }
    echo 'self.close();'."\n";
    echo '-->'."\n";
    echo '</script>'."\n";
    exit;
}


/**
 * Given an array of value, creates a popup menu to be part of a form
 * $options["value"]["label"]
 *
 * @param    type description
 * @todo Finish documenting this function
 */
function choose_from_menu ($options, $name, $selected='', $nothing='choose', $script='', 
                           $nothingvalue='0', $return=false, $disabled=false, $tabindex=0) {

    if ($nothing == 'choose') {
        $nothing = __gettext('Choose') .'...';
    }

    $attributes = ($script) ? 'onchange="'. $script .'"' : '';
    if ($disabled) {
        $attributes .= ' disabled="disabled"';
    }

    if ($tabindex) {
        $attributes .= ' tabindex="'.$tabindex.'"';
    }

    $output = '<select id="menu'.$name.'" name="'. $name .'" '. $attributes .'>' . "\n";
    if ($nothing) {
        $output .= '   <option value="'. $nothingvalue .'"'. "\n";
        if ($nothingvalue === $selected) {
            $output .= ' selected="selected"';
        }
        $output .= '>'. $nothing .'</option>' . "\n";
    }
    if (!empty($options)) {
        foreach ($options as $value => $label) {
            $output .= '   <option value="'. $value .'"';
            if ($value === $selected) {
                $output .= ' selected="selected"';
            }
            if ($label === '') {
                $output .= '>'. $value .'</option>' . "\n";
            } else {
                $output .= '>'. $label .'</option>' . "\n";
            }
        }
    }
    $output .= '</select>' . "\n";

    if ($return) {
        return $output;
    } else {
        echo $output;
    }
}

/**
 * Just like choose_from_menu, but takes a nested array (2 levels) and makes a dropdown menu
 * including option headings with the first level.
 */
function choose_from_menu_nested($options,$name,$selected='',$nothing='choose',$script = '',
                                 $nothingvalue=0,$return=false,$disabled=false,$tabindex=0) {

   if ($nothing == 'choose') {
        $nothing = __gettext('Choose') .'...';
    }

    $attributes = ($script) ? 'onchange="'. $script .'"' : '';
    if ($disabled) {
        $attributes .= ' disabled="disabled"';
    }

    if ($tabindex) {
        $attributes .= ' tabindex="'.$tabindex.'"';
    }

    $output = '<select id="menu'.$name.'" name="'. $name .'" '. $attributes .'>' . "\n";
    if ($nothing) {
        $output .= '   <option value="'. $nothingvalue .'"'. "\n";
        if ($nothingvalue === $selected) {
            $output .= ' selected="selected"';
        }
        $output .= '>'. $nothing .'</option>' . "\n";
    }
    if (!empty($options)) {
        foreach ($options as $section => $values) {
            $output .= '   <optgroup label="'.$section.'">'."\n";
            foreach ($values as $value => $label) {
                $output .= '   <option value="'. $value .'"';
                if ($value === $selected) {
                    $output .= ' selected="selected"';
                }
                if ($label === '') {
                    $output .= '>'. $value .'</option>' . "\n";
                } else {
                    $output .= '>'. $label .'</option>' . "\n";
                }
            }
            $output .= '   </optgroup>'."\n";
        }
    }
    $output .= '</select>' . "\n";

    if ($return) {
        return $output;
    } else {
        echo $output;
    }
}


/**
 * Given an array of values, creates a group of radio buttons to be part of a form
 * 
 * @param array  $options  An array of value-label pairs for the radio group (values as keys)
 * @param string $name     Name of the radiogroup (unique in the form)
 * @param string $checked  The value that is already checked
 */
function choose_from_radio ($options, $name, $checked='') {

    static $idcounter = 0;

    if (!$name) {
        $name = 'unnamed';
    }

    $output = '<span class="radiogroup '.$name."\">\n";

    if (!empty($options)) {
        $currentradio = 0;
        foreach ($options as $value => $label) {
            $htmlid = 'auto-rb'.sprintf('%04d', ++$idcounter);
            $output .= ' <span class="radioelement '.$name.' rb'.$currentradio."\">";
            $output .= '<input name="'.$name.'" id="'.$htmlid.'" type="radio" value="'.$value.'"';
            if ($value == $checked) {
                $output .= ' checked="checked"';
            }
            if ($label === '') {
                $output .= ' /> <label for="'.$htmlid.'">'.  $value .'</label></span>' .  "\n";
            } else {
                $output .= ' /> <label for="'.$htmlid.'">'.  $label .'</label></span>' .  "\n";
            }
            $currentradio = ($currentradio + 1) % 2;
        }
    }

    $output .= '</span>' .  "\n";

    echo $output;
}

/** Display an standard html checkbox with an optional label
 *
 * @param string  $name    The name of the checkbox
 * @param string  $value   The valus that the checkbox will pass when checked
 * @param boolean $checked The flag to tell the checkbox initial state
 * @param string  $label   The label to be showed near the checkbox
 * @param string  $alt     The info to be inserted in the alt tag
 */
function print_checkbox ($name, $value, $checked = true, $label = '', $alt = '', $script='',$return=false) {

    static $idcounter = 0;

    if (!$name) {
        $name = 'unnamed';
    }

    if (!$alt) {
        $alt = 'checkbox';
    }

    if ($checked) {
        $strchecked = ' checked="checked"';
    }

    $htmlid = 'auto-cb'.sprintf('%04d', ++$idcounter);
    $output  = '<span class="checkbox '.$name."\">";
    $output .= '<input name="'.$name.'" id="'.$htmlid.'" type="checkbox" value="'.$value.'" alt="'.$alt.'"'.$strchecked.' '.((!empty($script)) ? ' onclick="'.$script.'" ' : '').' />';
    if(!empty($label)) {
        $output .= ' <label for="'.$htmlid.'">'.$label.'</label>';
    }
    $output .= '</span>'."\n";

    if (empty($return)) {
        echo $output;
    } else {
        return $output;
    }

}

/** Display an standard html text field with an optional label
 *
 * @param string  $name    The name of the text field
 * @param string  $value   The value of the text field
 * @param string  $label   The label to be showed near the text field
 * @param string  $alt     The info to be inserted in the alt tag
 */
function print_textfield ($name, $value, $alt = '',$size=50,$maxlength= 0,$return=false) {

    static $idcounter = 0;

    if (empty($name)) {
        $name = 'unnamed';
    }

    if (empty($alt)) {
        $alt = 'textfield';
    }

    if (!empty($maxlength)) {
        $maxlength = ' maxlength="'.$maxlength.'" ';
    }

    $htmlid = 'auto-cb'.sprintf('%04d', ++$idcounter);
    $output  = '<span class="textfield '.$name."\">";
    $output .= '<input name="'.$name.'" id="'.$htmlid.'" type="text" value="'.$value.'" size="'.$size.'" '.$maxlength.' alt="'.$alt.'" />';
 
    $output .= '</span>'."\n";

    if (empty($return)) {
        echo $output;
    } else {
        return $output;
    }

}


/**
 * Validates an email to make sure it makes sense and adheres
 * to the email filter if it's set.
 *
 * @param string $address The email address to validate.
 * @return boolean
 */
function validate_email($address) {

    global $CFG;
    
    if (ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.
                  '@'.
                  '[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.
                  '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$',
                  $address)) {
                      
                      if ($CFG->emailfilter != "") {
                          $domain = substr($address,strpos($address,"@")+1);
                          if (substr_count($CFG->emailfilter, $domain) == 0) {
                              return false;
                          }
                      }
                      
                      return true;
                      
                  } else {
                      return false;
                  }
}

/**
 * Check for bad characters ?
 *
 * @param string $string ?
 * @param int $allowdots ?
 * @todo Finish documenting this function - more detail needed in description as well as details on arguments
 */
function detect_munged_arguments($string, $allowdots=1) {
    if (substr_count($string, '..') > $allowdots) {   // Sometimes we allow dots in references
        return true;
    }
    if (ereg('[\|\`]', $string)) {  // check for other bad characters
        return true;
    }
    if (empty($string) or $string == '/') {
        return true;
    }

    return false;
}



/**
 * Just returns an array of text formats suitable for a popup menu
 *
 * @uses FORMAT_MOODLE
 * @uses FORMAT_HTML
 * @uses FORMAT_PLAIN
 * @uses FORMAT_MARKDOWN
 * @return array
 */
function format_text_menu() {

    return array (FORMAT_MOODLE => __gettext('Elgg auto-format'),
                  FORMAT_HTML   => __gettext('HTML format'),
                  FORMAT_PLAIN  => __gettext('Plain text format'),
                  FORMAT_MARKDOWN  => __gettext('Markdown format'));
}

/*
 * Given text in a variety of format codings, this function returns
 * the text as safe HTML.
 *
 * @uses $CFG
 * @uses FORMAT_MOODLE
 * @uses FORMAT_HTML
 * @uses FORMAT_PLAIN
 * @uses FORMAT_WIKI
 * @uses FORMAT_MARKDOWN
 * @param string $text The text to be formatted. This is raw text originally from user input.
 * @param int $format Identifier of the text format to be used
 *            (FORMAT_MOODLE, FORMAT_HTML, FORMAT_PLAIN, FORMAT_WIKI, FORMAT_MARKDOWN)
 * @param  array $options ?
 * @param int $courseid ?
 * @return string
 * @todo Finish documenting this function
 */
function format_text($text, $format=FORMAT_MOODLE, $options=NULL, $courseid=NULL ) {

    global $CFG, $course;

    if (!isset($options->noclean)) {
        $options->noclean=false;
    }
    if (!isset($options->smiley)) {
        $options->smiley=true;
    }
    if (!isset($options->filter)) {
        $options->filter=true;
    }
    if (!isset($options->para)) {
        $options->para=true;
    }
    if (!isset($options->newlines)) {
        $options->newlines=true;
    }

    if (empty($courseid)) {
        if (!empty($course->id)) {         // An ugly hack for better compatibility
            $courseid = $course->id;
        }
    }

    /*
    if (!empty($CFG->cachetext)) {
        $time = time() - $CFG->cachetext;
        $md5key = md5($text.'-'.$courseid.$options->noclean.$options->smiley.$options->filter.$options->para.$options->newlines);
        if ($cacheitem = get_record_select('cache_text', "md5key = '$md5key' AND timemodified > '$time'")) {
            return $cacheitem->formattedtext;
        }
    }
    */ // DISABLED - there is no cache_text - Penny

    $CFG->currenttextiscacheable = true;   // Default status - can be changed by any filter

    switch ($format) {
        case FORMAT_HTML:

            if (!empty($options->smiley)) {
                replace_smilies($text);
            }

            if (!isset($options->noclean)) {
                $text = clean_text($text, $format, !empty($options->cleanuserfile));
            }

            if (!empty($options->filter)) {
                $text = filter_text($text, $courseid);
            }
            break;

        case FORMAT_PLAIN:
            $text = s($text); 
            $text = rebuildnolinktag($text);
            $text = str_replace('  ', '&nbsp; ', $text);
            $text = nl2br($text);
            break;

        case FORMAT_WIKI:
            // this format is deprecated
            $text = '<p>NOTICE: Wiki-like formatting has been removed from Moodle.  You should not be seeing
                     this message as all texts should have been converted to Markdown format instead.
                     Please post a bug report to http://moodle.org/bugs with information about where you
                     saw this message.</p>'.s($text);
            break;

        case FORMAT_MARKDOWN:
            $text = markdown_to_html($text);
            if (!empty($options->smiley)) {
                replace_smilies($text);
            }
            if (empty($options->noclean)) {
                $text = clean_text($text, $format);
            }
            if (!empty($options->filter)) {
                $text = filter_text($text, $courseid);
            }
            break;

        default:  // FORMAT_MOODLE or anything else
            $text = text_to_html($text, $options->smiley, $options->para, $options->newlines);
            if (empty($options->noclean)) {
                $text = clean_text($text, $format);
            }
            if (!empty($options->filter)) {
                $text = filter_text($text, $courseid);
            }
            break;
    }

    if (!empty($CFG->cachetext) and $CFG->currenttextiscacheable) {
        $newrecord->md5key = $md5key;
        $newrecord->formattedtext = $text;
        $newrecord->timemodified = time();
        @insert_record('cache_text', $newrecord);
    }

    return $text;
}

/** Converts the text format from the value to the 'internal'
 *  name or vice versa. $key can either be the value or the name
 *  and you get the other back.
 *  
 *  @param mixed int 0-4 or string one of 'moodle','html','plain','markdown'
 *  @return mixed as above but the other way around!
 */
function text_format_name( $key ) {
  $lookup = array();
  $lookup[FORMAT_MOODLE] = 'moodle';
  $lookup[FORMAT_HTML] = 'html';
  $lookup[FORMAT_PLAIN] = 'plain';
  $lookup[FORMAT_MARKDOWN] = 'markdown';
  $value = "error";
  if (!is_numeric($key)) {
    $key = strtolower( $key );
    $value = array_search( $key, $lookup );
  }
  else {
    if (isset( $lookup[$key] )) {
      $value =  $lookup[ $key ];
    }
  }
  return $value;
}

/** Given a simple string, this function returns the string
 *  processed by enabled filters if $CFG->filterall is enabled
 *
 *  @param string  $string     The string to be filtered.
 *  @param boolean $striplinks To strip any link in the result text.
 *  @param int     $courseid   Current course as filters can, potentially, use it
 *  @return string
 */
function format_string ($string, $striplinks = false, $courseid=NULL ) {

    global $CFG, $course;

    //We'll use a in-memory cache here to speed up repeated strings
    static $strcache;

    //Calculate md5
    $md5 = md5($string.'<+>'.$striplinks);

    //Fetch from cache if possible
    if(isset($strcache[$md5])) {
        return $strcache[$md5];
    }

    if (empty($courseid)) {
        if (!empty($course->id)) {         // An ugly hack for better compatibility
            $courseid = $course->id;       // (copied from format_text)
        }
    }

    if (!empty($CFG->filterall)) {
        $string = filter_text($string, $courseid);
    }

    if ($striplinks) {  //strip links in string
        $string = preg_replace('/(<a[^>]+?>)(.+?)(<\/a>)/is','$2',$string);
    }

    //Store to cache
    $strcache[$md5] = $string;

    return $string;
}

/**
 * Given text in a variety of format codings, this function returns
 * the text as plain text suitable for plain email.
 *
 * @uses FORMAT_MOODLE
 * @uses FORMAT_HTML
 * @uses FORMAT_PLAIN
 * @uses FORMAT_WIKI
 * @uses FORMAT_MARKDOWN
 * @param string $text The text to be formatted. This is raw text originally from user input.
 * @param int $format Identifier of the text format to be used
 *            (FORMAT_MOODLE, FORMAT_HTML, FORMAT_PLAIN, FORMAT_WIKI, FORMAT_MARKDOWN)
 * @return string
 */
function format_text_email($text, $format) {

    switch ($format) {

        case FORMAT_PLAIN:
            return $text;
            break;

        case FORMAT_WIKI:
            $text = wiki_to_html($text);
        /// This expression turns links into something nice in a text format. (Russell Jungwirth)
        /// From: http://php.net/manual/en/function.eregi-replace.php and simplified
            $text = eregi_replace('(<a [^<]*href=["|\']?([^ "\']*)["|\']?[^>]*>([^<]*)</a>)','\\3 [ \\2 ]', $text);
            return strtr(strip_tags($text), array_flip(get_html_translation_table(HTML_ENTITIES)));
            break;

        case FORMAT_HTML:
            return html_to_text($text);
            break;

        case FORMAT_MOODLE:
        case FORMAT_MARKDOWN:
        default:
            $text = eregi_replace('(<a [^<]*href=["|\']?([^ "\']*)["|\']?[^>]*>([^<]*)</a>)','\\3 [ \\2 ]', $text);
            return strtr(strip_tags($text), array_flip(get_html_translation_table(HTML_ENTITIES)));
            break;
    }
}

/**
 * This function takes a string and examines it for HTML tags.
 * If tags are detected it passes the string to a helper function {@link cleanAttributes2()}
 *  which checks for attributes and filters them for malicious content
 *         17/08/2004              ::          Eamon DOT Costello AT dcu DOT ie
 *
 * @param string $str The string to be examined for html tags
 * @return string
 */
function cleanAttributes($str){
    $result = preg_replace_callback(
            '%(<[^>]*(>|$)|>)%m', #search for html tags
            "cleanAttributes2",
            $str
            );
    return  $result;
}

/**
 * This function takes a string with an html tag and strips out any unallowed
 * protocols e.g. javascript:
 * It calls ancillary functions in kses which are prefixed by kses
*        17/08/2004              ::          Eamon DOT Costello AT dcu DOT ie
 *
 * @param array $htmlArray An array from {@link cleanAttributes()}, containing in its 1st
 *              element the html to be cleared
 * @return string
 */
function cleanAttributes2($htmlArray){

    global $CFG, $ALLOWED_PROTOCOLS;
    require_once($CFG->libdir .'/kses.php');

    $htmlTag = $htmlArray[1];
    if (substr($htmlTag, 0, 1) != '<') {
        return '&gt;';  //a single character ">" detected
    }
    if (!preg_match('%^<\s*(/\s*)?([a-zA-Z0-9]+)([^>]*)>?$%', $htmlTag, $matches)) {
        return ''; // It's seriously malformed
    }
    $slash = trim($matches[1]); //trailing xhtml slash
    $elem = $matches[2];    //the element name
    $attrlist = $matches[3]; // the list of attributes as a string

    $attrArray = kses_hair($attrlist, $ALLOWED_PROTOCOLS);

    $attStr = '';
    foreach ($attrArray as $arreach) {
        $attStr .=  ' '.strtolower($arreach['name']).'="'.$arreach['value'].'" ';
    }

    // Remove last space from attribute list
    $attStr = rtrim($attStr);

    $xhtml_slash = '';
    if (preg_match('%/\s*$%', $attrlist)) {
        $xhtml_slash = ' /';
    }
    return '<'. $slash . $elem . $attStr . $xhtml_slash .'>';
}

/**
 * Replaces all known smileys in the text with image equivalents
 *
 * @uses $CFG
 * @param string $text Passed by reference. The string to search for smily strings.
 * @return string
 */
function replace_smilies(&$text) {
///
    global $CFG;

/// this builds the mapping array only once
    static $runonce = false;
    static $e = array();
    static $img = array();
    static $emoticons = array(
        ':-)'  => 'smiley',
        ':)'   => 'smiley',
        ':-D'  => 'biggrin',
        ';-)'  => 'wink',
        ':-/'  => 'mixed',
        'V-.'  => 'thoughtful',
        ':-P'  => 'tongueout',
        'B-)'  => 'cool',
        '^-)'  => 'approve',
        '8-)'  => 'wideeyes',
        ':o)'  => 'clown',
        ':-('  => 'sad',
        ':('   => 'sad',
        '8-.'  => 'shy',
        ':-I'  => 'blush',
        ':-X'  => 'kiss',
        '8-o'  => 'surprise',
        'P-|'  => 'blackeye',
        '8-['  => 'angry',
        'xx-P' => 'dead',
        '|-.'  => 'sleepy',
        '}-]'  => 'evil',
        );

    if ($runonce == false) {  /// After the first time this is not run again
        foreach ($emoticons as $emoticon => $image){
            $alttext = get_string($image, 'pix');

            $e[] = $emoticon;
            $img[] = '<img alt="'. $alttext .'" width="15" height="15" src="'. $CFG->pixpath .'/s/'. $image .'.gif" />';
        }
        $runonce = true;
    }

    // Exclude from transformations all the code inside <script> tags
    // Needed to solve Bug 1185. Thanks to jouse 2001 detecting it. :-)
    // Based on code from glossary fiter by Williams Castillo.
    //       - Eloy

    // Detect all the <script> zones to take out
    $excludes = array();
    preg_match_all('/<script language(.+?)<\/script>/is',$text,$list_of_excludes);

    // Take out all the <script> zones from text
    foreach (array_unique($list_of_excludes[0]) as $key=>$value) {
        $excludes['<+'.$key.'+>'] = $value;
    }
    if ($excludes) {
        $text = str_replace($excludes,array_keys($excludes),$text);
    }

/// this is the meat of the code - this is run every time
    $text = str_replace($e, $img, $text);

    // Recover all the <script> zones to text
    if ($excludes) {
        $text = str_replace(array_keys($excludes),$excludes,$text);
    }
}

/**
 * Given plain text, makes it into HTML as nicely as possible.
 * May contain HTML tags already
 *
 * @uses $CFG
 * @param string $text The string to convert.
 * @param boolean $smiley Convert any smiley characters to smiley images?
 * @param boolean $para If true then the returned string will be wrapped in paragraph tags
 * @param boolean $newlines If true then lines newline breaks will be converted to HTML newline breaks.
 * @return string
 */

function text_to_html($text, $smiley=true, $para=true, $newlines=true) {
///

    global $CFG;

/// Remove any whitespace that may be between HTML tags
    $text = eregi_replace(">([[:space:]]+)<", "><", $text);

/// Remove any returns that precede or follow HTML tags
    $text = eregi_replace("([\n\r])<", " <", $text);
    $text = eregi_replace(">([\n\r])", "> ", $text);

    convert_urls_into_links($text);

/// Make returns into HTML newlines.
    if ($newlines) {
        $text = nl2br($text);
    }

/// Turn smileys into images.
    if ($smiley) {
        replace_smilies($text);
    }

/// Wrap the whole thing in a paragraph tag if required
    if ($para) {
        return '<p>'.$text.'</p>';
    } else {
        return $text;
    }
}

/**
 * Given Markdown formatted text, make it into XHTML using external function
 *
 * @uses $CFG
 * @param string $text The markdown formatted text to be converted.
 * @return string Converted text
 */
function markdown_to_html($text) {
    global $CFG;

    require_once($CFG->libdir .'/markdown.php');

    return Markdown($text);
}

/**
 * Given HTML text, make it into plain text using external function
 *
 * @uses $CFG
 * @param string $html The text to be converted.
 * @return string
 */
function html_to_text($html) {

    global $CFG;

    require_once($CFG->libdir .'/html2text.php');

    return html2text($html);
}

/**
 * Given some text this function converts any URLs it finds into HTML links
 *
 * @param string $text Passed in by reference. The string to be searched for urls.
 */
function convert_urls_into_links(&$text) {
/// Make lone URLs into links.   eg http://moodle.com/
    $text = eregi_replace("([[:space:]]|^|\(|\[)([[:alnum:]]+)://([^[:space:]]*)([[:alnum:]#?/&=])",
                          "\\1<a href=\"\\2://\\3\\4\" target=\"_blank\">\\2://\\3\\4</a>", $text);

/// eg www.moodle.com
    $text = eregi_replace("([[:space:]]|^|\(|\[)www\.([^[:space:]]*)([[:alnum:]#?/&=])",
                          "\\1<a href=\"http://www.\\2\\3\" target=\"_blank\">www.\\2\\3</a>", $text);
}



/**
 * This function will highlight search words in a given string
 * It cares about HTML and will not ruin links.  It's best to use
 * this function after performing any conversions to HTML.
 * Function found here: http://forums.devshed.com/t67822/scdaa2d1c3d4bacb4671d075ad41f0854.html
 *
 * @param string $needle The string to search for
 * @param string $haystack The string to search for $needle in
 * @param int $case ?
 * @return string
 * @todo Finish documenting this function
 */
function highlight($needle, $haystack, $case=0,
                    $left_string='<span class="highlight">', $right_string='</span>') {
    if (empty($needle)) {
        return $haystack;
    }

    //$list_of_words = eregi_replace("[^-a-zA-Z0-9&.']", " ", $needle);  // bug 3101
    $list_of_words = $needle;
    $list_array = explode(' ', $list_of_words);
    for ($i=0; $i<sizeof($list_array); $i++) {
        if (strlen($list_array[$i]) == 1) {
            $list_array[$i] = '';
        }
    }
    $list_of_words = implode(' ', $list_array);
    $list_of_words_cp = $list_of_words;
    $final = array();
    preg_match_all('/<(.+?)>/is',$haystack,$list_of_words);

    foreach (array_unique($list_of_words[0]) as $key=>$value) {
        $final['<|'.$key.'|>'] = $value;
    }

    $haystack = str_replace($final,array_keys($final),$haystack);
    $list_of_words_cp = eregi_replace(' +', '|', $list_of_words_cp);

    if ($list_of_words_cp{0}=='|') {
        $list_of_words_cp{0} = '';
    }
    if ($list_of_words_cp{strlen($list_of_words_cp)-1}=='|') {
        $list_of_words_cp{strlen($list_of_words_cp)-1}='';
    }

    $list_of_words_cp = trim($list_of_words_cp);

    if ($list_of_words_cp) {

      $list_of_words_cp = "(". $list_of_words_cp .")";

      if (!$case){
        $haystack = eregi_replace("$list_of_words_cp", "$left_string"."\\1"."$right_string", $haystack);
      } else {
        $haystack = ereg_replace("$list_of_words_cp", "$left_string"."\\1"."$right_string", $haystack);
      }
    }
    $haystack = str_replace(array_keys($final),$final,$haystack);

    return $haystack;
}

/**
 * This function will highlight instances of $needle in $haystack
 * It's faster that the above function and doesn't care about
 * HTML or anything.
 *
 * @param string $needle The string to search for
 * @param string $haystack The string to search for $needle in
 * @return string
 */
function highlightfast($needle, $haystack) {
    $textlib = textlib_get_instance();

    $parts = explode($textlib->strtolower($needle), $textlib->strtolower($haystack));

    $pos = 0;

    foreach ($parts as $key => $part) {
        $parts[$key] = $textlib->substr($haystack, $pos, $textlib->strlen($part));
        $pos += $textlib->strlen($part);

        $parts[$key] .= '<span class="highlight">'.$textlib->substr($haystack, $pos, $textlib->strlen($needle)).'</span>';
        $pos += $textlib->strlen($needle);
    }

    return (join('', $parts));
}

/**
 * Print a link to continue on to another page.
 *
 * @uses $CFG
 * @param string $link The url to create a link to.
 */
function print_continue($link) {

    global $CFG;

    if (!$link) {
        $link = $_SERVER['HTTP_REFERER'];
    }

    echo '<div class="continuebutton">';
    print_single_button($link, NULL, __gettext('Continue'), 'post', $CFG->framename);
    echo '</div>'."\n";
}

/**
 * Print a message in a standard themed box.
 *
 * @param string $message ?
 * @param string $align ?
 * @param string $width ?
 * @param string $color ?
 * @param int $padding ?
 * @param string $class ?
 * @todo Finish documenting this function
 */
function print_simple_box($message, $align='', $width='', $color='', $padding=5, $class='generalbox', $id='') {
    print_simple_box_start($align, $width, $color, $padding, $class, $id);
    echo stripslashes_safe($message);
    print_simple_box_end();
}

/**
 * Print the top portion of a standard themed box.
 *
 * @param string $align ?
 * @param string $width ?
 * @param string $color ?
 * @param int $padding ?
 * @param string $class ?
 * @todo Finish documenting this function
 */
function print_simple_box_start($align='', $width='', $color='', $padding=5, $class='generalbox', $id='') {

    if ($color) {
        $color = 'bgcolor="'. $color .'"';
    }
    if ($align) {
        $align = 'align="'. $align .'"';
    }
    if ($width) {
        $width = 'width="'. $width .'"';
    }
    if ($id) {
        $id = 'id="'. $id .'"';
    }
    $class        = trim($class);
    $classcontent = preg_replace('/(\s+|$)/','content ', $class);

    echo "<table $align $width $id class=\"$class\" border=\"0\" cellpadding=\"$padding\" cellspacing=\"0\">".
         "<tr><td $color class=\"$classcontent\">";
}

/**
 * Print the end portion of a standard themed box.
 */
function print_simple_box_end() {
    echo '</td></tr></table>';
}

/**
 * Print a self contained form with a single submit button.
 *
 * @param string $link ?
 * @param array $options ?
 * @param string $label ?
 * @param string $method ?
 * @todo Finish documenting this function
 */
function print_single_button($link, $options, $label='OK', $method='get', $target='_self') {
    echo '<div class="singlebutton">';
    echo '<form action="'. $link .'" method="'. $method .'" target="'.$target.'">';
    if ($options) {
        foreach ($options as $name => $value) {
            echo '<input type="hidden" name="'. $name .'" value="'. $value .'" />';
        }
    }
    echo '<input type="submit" value="'. $label .'" /></form></div>';
}

/**
 * Print a png image.
 *
 * @param string $url ?
 * @param int $sizex ?
 * @param int $sizey ?
 * @param boolean $returnstring ?
 * @param string $parameters ?
 * @todo Finish documenting this function
 */
function print_png($url, $sizex, $sizey, $returnstring, $parameters='alt=""') {
    global $CFG;
    static $recentIE;

    if (!isset($recentIE)) {
        $recentIE = check_browser_version('MSIE', '5.0');
    }

    if ($recentIE) {  // work around the HORRIBLE bug IE has with alpha transparencies
        $output .= '<img src="'. $CFG->pixpath .'/spacer.gif" width="'. $sizex .'" height="'. $sizey .'"'.
                   ' border="0" class="png" style="width: '. $sizex .'px; height: '. $sizey .'px; '.
                   ' filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='.
                   "'$url', sizingMethod='scale') ".
                   ' '. $parameters .' />';
    } else {
        $output .= '<img src="'. $url .'" border="0" width="'. $sizex .'" height="'. $sizey .'" '.
                   ' '. $parameters .' />';
    }

    if ($returnstring) {
        return $output;
    } else {
        echo $output;
    }
}

/**
 * Print a nicely formatted table.
 *
 * @param array $table is an object with several properties.
 *     <ul<li>$table->head - An array of heading names.
 *     <li>$table->align - An array of column alignments
 *     <li>$table->size  - An array of column sizes
 *     <li>$table->wrap - An array of "nowrap"s or nothing
 *     <li>$table->data[] - An array of arrays containing the data.
 *     <li>$table->width  - A percentage of the page
 *     <li>$table->cellpadding  - Padding on each cell
 *     <li>$table->cellspacing  - Spacing between cells
 * </ul>
 * @return boolean
 * @todo Finish documenting this function
 */
function print_table($table) {

    if (isset($table->align)) {
        foreach ($table->align as $key => $aa) {
            if ($aa) {
                $align[$key] = ' align="'. $aa .'"';
            } else {
                $align[$key] = '';
            }
        }
    }
    if (isset($table->size)) {
        foreach ($table->size as $key => $ss) {
            if ($ss) {
                $size[$key] = ' width="'. $ss .'"';
            } else {
                $size[$key] = '';
            }
        }
    }
    if (isset($table->wrap)) {
        foreach ($table->wrap as $key => $ww) {
            if ($ww) {
                $wrap[$key] = ' nowrap="nowrap" ';
            } else {
                $wrap[$key] = '';
            }
        }
    }

    if (empty($table->width)) {
        $table->width = '80%';
    }

    if (empty($table->cellpadding)) {
        $table->cellpadding = '5';
    }

    if (empty($table->cellspacing)) {
        $table->cellspacing = '1';
    }

    if (empty($table->class)) {
        $table->class = 'generaltable';
    }

    $tableid = empty($table->id) ? '' : 'id="'.$table->id.'"';

    print_simple_box_start('center', $table->width, '#ffffff', 0);
    echo '<table width="100%" border="0" align="center" ';
    echo " cellpadding=\"$table->cellpadding\" cellspacing=\"$table->cellspacing\" class=\"$table->class\" $tableid>\n";

    $countcols = 0;

    if (!empty($table->head)) {
        $countcols = count($table->head);
        echo '<tr>';
        foreach ($table->head as $key => $heading) {

            if (!isset($size[$key])) {
                $size[$key] = '';
            }
            if (!isset($align[$key])) {
                $align[$key] = '';
            }
            echo '<th valign="top" '. $align[$key].$size[$key] .' nowrap="nowrap" class="header c'.$key.'">'. $heading .'</th>';
        }
        echo '</tr>'."\n";
    }

    if (!empty($table->data)) {
        $oddeven = 1;
        foreach ($table->data as $key => $row) {
            $oddeven = $oddeven ? 0 : 1;
            echo '<tr class="r'.$oddeven.'">'."\n";
            if ($row == 'hr' and $countcols) {
                echo '<td colspan="'. $countcols .'"><div class="tabledivider"></div></td>';
            } else {  /// it's a normal row of data
                foreach ($row as $key => $item) {
                    if (!isset($size[$key])) {
                        $size[$key] = '';
                    }
                    if (!isset($align[$key])) {
                        $align[$key] = '';
                    }
                    if (!isset($wrap[$key])) {
                        $wrap[$key] = '';
                    }
                    echo '<td '. $align[$key].$size[$key].$wrap[$key] .' class="cell c'.$key.'">'. $item .'</td>';
                }
            }
            echo '</tr>'."\n";
        }
    }
    echo '</table>'."\n";
    print_simple_box_end();

    return true;
}

/**
 * Prints a basic textarea field.
 *
 * @uses $CFG
 * @param boolean $usehtmleditor ?
 * @param int $rows ?
 * @param int $cols ?
 * @param null $width <b>Legacy field no longer used!</b>  Set to zero to get control over mincols
 * @param null $height <b>Legacy field no longer used!</b>  Set to zero to get control over minrows
 * @param string $name ?
 * @param string $value ?
 * @param int $courseid ?
 * @todo Finish documenting this function
 */
function print_textarea($usehtmleditor, $rows, $cols, $width, $height, $name, $value='', $courseid=0) {
/// $width and height are legacy fields and no longer used as pixels like they used to be.
/// However, you can set them to zero to override the mincols and minrows values below.

    global $CFG, $course;
    static $scriptcount; // For loading the htmlarea script only once.

    $mincols = 65;
    $minrows = 10;

    if (empty($courseid)) {
        if (!empty($course->id)) {  // search for it in global context
            $courseid = $course->id;
        }
    }

    if (empty($scriptcount)) {
        $scriptcount = 0;
    }

    if ($usehtmleditor) {

        if (!empty($courseid) and isteacher($courseid)) {
            echo ($scriptcount < 1) ? '<script type="text/javascript" src="'. $CFG->wwwroot .'lib/editor/htmlarea.php?id='. $courseid .'"></script>'."\n" : '';
        } else {
            echo ($scriptcount < 1) ? '<script type="text/javascript" src="'. $CFG->wwwroot .'lib/editor/htmlarea.php"></script>'."\n" : '';
        }
        echo ($scriptcount < 1) ? '<script type="text/javascript" src="'. $CFG->wwwroot .'lib/editor/lang/en.php"></script>'."\n" : '';
        $scriptcount++;

        if ($height) {    // Usually with legacy calls
            if ($rows < $minrows) {
                $rows = $minrows;
            }
        }
        if ($width) {    // Usually with legacy calls
            if ($cols < $mincols) {
                $cols = $mincols;
            }
        }
    }

    echo '<textarea id="edit-'. $name .'" name="'. $name .'" rows="'. $rows .'" cols="'. $cols .'">';
    if ($usehtmleditor) {
        echo htmlspecialchars(stripslashes_safe($value), ENT_COMPAT, 'utf-8'); // needed for editing of cleaned text!
    } else {
        p ($value);
    }
    echo '</textarea>'."\n";
}

/**
 * Legacy function, provided for backward compatability.
 * This method now simply calls {@link use_html_editor()}
 *
 * @deprecated Use {@link use_html_editor()} instead.
 * @param string $name Form element to replace with HTMl editor by name
 * @todo Finish documenting this function
 */
function print_richedit_javascript($form, $name, $source='no') {
    use_html_editor($name);
}


/**
 * Sets up the HTML editor on textareas in the current page.
 * If a field name is provided, then it will only be
 * applied to that field - otherwise it will be used
 * on every textarea in the page.
 *
 * In most cases no arguments need to be supplied
 *
 * @param string $name Form element to replace with HTMl editor by name
 */
function use_html_editor($name='', $editorhidebuttons='') {
    echo '<script language="javascript" type="text/javascript" defer="defer">'."\n";
    print_editor_config($editorhidebuttons);
    if (empty($name)) {
        echo "\n".'HTMLArea.replaceAll(config);'."\n";
    } else {
        echo "\nHTMLArea.replace('edit-$name', config);\n";
    }
    echo '</script>'."\n";
}


/**
 * Prints form items with the names $day, $month and $year
 *
 * @param int $day ?
 * @param int $month ?
 * @param int $year ?
 * @param int $currenttime A default timestamp in GMT
 * @todo Finish documenting this function
 */
function print_date_selector($day, $month, $year, $currenttime=0) {

    if (!$currenttime) {
        $currenttime = time();
    }
    $currentdate = usergetdate($currenttime);

    for ($i=1; $i<=31; $i++) {
        $days[$i] = $i;
    }
    for ($i=1; $i<=12; $i++) {
        $months[$i] = userdate(gmmktime(12,0,0,$i,1,2000), "%B");
    }
    for ($i=2000; $i<=2010; $i++) {
        $years[$i] = $i;
    }
    choose_from_menu($days,   $day,   $currentdate['mday'], '');
    choose_from_menu($months, $month, $currentdate['mon'],  '');
    choose_from_menu($years,  $year,  $currentdate['year'], '');
}

/**
 *Prints form items with the names $hour and $minute
 *
 * @param ? $hour ?
 * @param ? $minute ?
 * @param $currenttime A default timestamp in GMT
 * @param int $step ?
 * @todo Finish documenting this function
 */
function print_time_selector($hour, $minute, $currenttime=0, $step=5 ,$return=false) {

    if (!$currenttime) {
        $currenttime = time();
    }
    $currentdate = usergetdate($currenttime);
    if ($step != 1) {
        $currentdate['minutes'] = ceil($currentdate['minutes']/$step)*$step;
    }
    for ($i=0; $i<=23; $i++) {
        $hours[$i] = sprintf("%02d",$i);
    }
    for ($i=0; $i<=59; $i+=$step) {
        $minutes[$i] = sprintf("%02d",$i);
    }

    return choose_from_menu($hours,   $hour,   $currentdate['hours'],   '','','',$return)
        .choose_from_menu($minutes, $minute, $currentdate['minutes'], '','','',$return);
}

/**
 *Returns an A link tag
 *
 * @param $url
 * @param $str
 * @param $extrattr Extra attribs for the A tag
 * @return string
 */
function a_href($url, $str, $extrattr='') {
    $str =  htmlspecialchars($str, ENT_COMPAT, 'utf-8');
    return "<a href=\"$url\" $extrattr >$str</a>"; 
}

/**
 *Returns an A link tag using __gettext()
 *
 * @param $url
 * @param $str for __gettext()
 * @param $extrattr Extra attribs for the A tag
 * @return string
 */
function a_hrefg($url, $str, $extrattr='') {
    $str = htmlspecialchars(__gettext($str), ENT_COMPAT, 'utf-8');
    return "<a href=\"$url\" $extrattr >$str</a>"; 
}

/**
 * Print an error page displaying an error message.
 * Old method, don't call directly in new code - use print_error instead.
 *
 *
 * @uses $SESSION
 * @uses $CFG
 * @param string $message The message to display to the user about the error.
 * @param string $link The url where the user will be prompted to continue. If no url is provided the user will be directed to the site index page.
 */
function error ($message, $link='') {
    global $CFG, $SESSION;

    @header('HTTP/1.0 404 Not Found');

    print_header(__gettext('Error'));
    echo '<br />';

    $message = clean_text($message);   // In case nasties are in here

    print_simple_box($message, 'center', '', '#FFBBBB', 5, 'errorbox');

    if (!$link) {
        if ( !empty($SESSION->fromurl) ) {
            $link = $SESSION->fromurl;
            unset($SESSION->fromurl);
        } else {
            $link = $CFG->wwwroot;
        }
    }
    print_continue($link);
    print_footer();
    for ($i=0;$i<512;$i++) {  // Padding to help IE work with 404
        echo ' ';
    }
    die;
}

/**
 * Print an error page displaying an error message.  New method - use this for new code.
 *
 * @uses $SESSION
 * @uses $CFG
 * @param string $string The name of the string from error.php to print
 * @param string $link The url where the user will be prompted to continue. If no url is provided the user will be directed to the site index page.
 */
function print_error ($string, $link='') {

    $string = get_string($string, 'error');
    error($string, $link);
}



/**
 * Print a message and exit.
 *
 * @uses $CFG
 * @param string $message ?
 * @param string $link ?
 * @todo Finish documenting this function
 */
function notice ($message, $link='') {
    global $CFG;

    $message = clean_text($message);
    $link    = clean_text($link);

    if (!$link) {
        if (!empty($_SERVER['HTTP_REFERER'])) {
            $link = $_SERVER['HTTP_REFERER'];
        } else {
            $link = $CFG->wwwroot;
        }
    }

    echo '<br />';
    print_simple_box($message, 'center', '50%', '', '20', 'noticebox');
    print_continue($link);
    print_footer(get_site());
    die;
}

/**
 * Print a message along with "Yes" and "No" links for the user to continue.
 *
 * @param string $message The text to display
 * @param string $linkyes The link to take the user to if they choose "Yes"
 * @param string $linkno The link to take the user to if they choose "No"
 */
function notice_yesno ($message, $linkyes, $linkno) {

    global $CFG;

    $message = clean_text($message);
    $linkyes = clean_text($linkyes);
    $linkno = clean_text($linkno);

    print_simple_box_start('center', '60%', '', 5, 'noticebox', 'notice');
    echo '<p align="center">'. $message .'</p>';
    echo '<table align="center" cellpadding="20"><tr><td>';
    print_single_button($linkyes, NULL, __gettext('Yes'), 'post', $CFG->framename);
    echo '</td><td>';
    print_single_button($linkno, NULL, __gettext('No'), 'post', $CFG->framename);
    echo '</td></tr></table>';
    print_simple_box_end();
}

/**
 * Redirects the user to another page, after printing a notice
 *
 * @param string $url The url to take the user to
 * @param string $message The text message to display to the user about the redirect, if any
 * @param string $delay How long before refreshing to the new page at $url?
 * @todo '&' needs to be encoded into '&amp;' for XHTML compliance,
 *      however, this is not true for javascript. Therefore we
 *      first decode all entities in $url (since we cannot rely on)
 *      the correct input) and then encode for where it's needed
 *      echo "<script type='text/javascript'>alert('Redirect $url');</script>";
 */
function redirect($url, $message='', $delay='0') {

    $url     = clean_text($url);
    $message = clean_text($message);

    $url = htmlspecialchars_decode($url, ENT_COMPAT); // for php < 5.1.0 this is defined in elgglib.php
    $url = str_replace(array("\n", "\r"), '', $url); // some more cleaning
    $encodedurl = htmlspecialchars($url, ENT_COMPAT, 'utf-8');

    if (empty($message)) {
        echo '<meta http-equiv="refresh" content="'. $delay .'; url='. $encodedurl .'" />';
        echo '<script type="text/javascript">'. "\n" .'<!--'. "\n". "location.replace('$url');". "\n". '//-->'. "\n". '</script>';   // To cope with Mozilla bug
    } else {
        
        if (empty($delay)) {
            $delay = 3;  // There's no point having a message with no delay
        }
        print_header('', '', '', '', '<meta http-equiv="refresh" content="'. $delay .'; url='. $encodedurl .'" />');
        echo '<div style="text-align: center;">';
        echo '<p>'. $message .'</p>';
        //called from setup.php, so gettext module hasn't been loaded yet
        if (function_exists("__gettext")) {
            $continue = __gettext('Continue');
        } else {
            $continue = 'Continue';
        }
        echo '<p>( <a href="'. $encodedurl .'">'. $continue .'</a> )</p>';
        echo '</div>';

?>
<script type="text/javascript">
<!--

  function redirect() {
      document.location.replace('<?php echo $url ?>');
  }
  setTimeout("redirect()", <?php echo ($delay * 1000) ?>);
-->
</script>
<?php

    }
    die;
}

/**
 * Print a bold message in an optional color.
 *
 * @param string $message The message to print out
 * @param string $style Optional style to display message text in
 * @param string $align Alignment option
 */
function notify ($message, $style='notifyproblem', $align='center') {

    if ($style == 'green') {
        $style = 'notifysuccess';  // backward compatible with old color system
    }

    $message = clean_text($message);

    echo '<div class="'.$style.'" align="'. $align .'">'. $message .'</div>'."<br />\n";
}


/**
 * This function is used to rebuild the <nolink> tag because some formats (PLAIN and WIKI)
 * will transform it to html entities
 *
 * @param string $text Text to search for nolink tag in
 * @return string
 */
function rebuildnolinktag($text) {

    $text = preg_replace('/&lt;(\/*nolink)&gt;/i','<$1>',$text);

    return $text;
}


/**
 * Prints out the HTML editor config.
 *
 * @uses $CFG
 */
 function print_editor_config($editorhidebuttons='') {

    global $CFG;

    // print new config
    echo 'var config = new HTMLArea.Config();'."\n";
    echo "config.pageStyle = \"body {";
    if(!(empty($CFG->editorbackgroundcolor))) {
        echo " background-color: $CFG->editorbackgroundcolor;";
    }

    if(!(empty($CFG->editorfontfamily))) {
        echo " font-family: $CFG->editorfontfamily;";
    }

    if(!(empty($CFG->editorfontsize))) {
        echo " font-size: $CFG->editorfontsize;";
    }

    echo " }\";\n";
    echo "config.killWordOnPaste = ";
    echo(empty($CFG->editorkillword)) ? "false":"true";
    echo ';'."\n";
    echo 'config.fontname = {'."\n";

    $fontlist = isset($CFG->editorfontlist) ? explode(';', $CFG->editorfontlist) : array();
    $i = 1;                     // Counter is used to get rid of the last comma.

    foreach($fontlist as $fontline) {
        if(!empty($fontline)) {
            if ($i > 1) {
                echo ','."\n";
            }
            list($fontkey, $fontvalue) = split(':', $fontline);
            echo '"'. $fontkey ."\":\t'". $fontvalue ."'";

            $i++;
        }
    }
    echo '};';

    if (!empty($editorhidebuttons)) {
        echo "\nconfig.hideSomeButtons(\" ". $editorhidebuttons ." \");\n";
    } else if (!empty($CFG->editorhidebuttons)) {
        echo "\nconfig.hideSomeButtons(\" ". $CFG->editorhidebuttons ." \");\n";
    }

    if(!empty($CFG->editorspelling) && !empty($CFG->aspellpath)) {
        print_speller_code($usehtmleditor=true);
    }
}

/**
 * Prints out code needed for spellchecking.
 * Original idea by Ludo (Marc Alier).
 *
 * @uses $CFG
 * @param boolean $usehtmleditor ?
 * @todo Finish documenting this function
 */
function print_speller_code ($usehtmleditor=false) {
    global $CFG;

    if(!$usehtmleditor) {
        echo "\n".'<script language="javascript" type="text/javascript">'."\n";
        echo 'function openSpellChecker() {'."\n";
        echo "\tvar speller = new spellChecker();\n";
        echo "\tspeller.popUpUrl = \"" . $CFG->wwwroot ."lib/speller/spellchecker.html\";\n";
        echo "\tspeller.spellCheckScript = \"". $CFG->wwwroot ."lib/speller/server-scripts/spellchecker.php\";\n";
        echo "\tspeller.spellCheckAll();\n";
        echo '}'."\n";
        echo '</script>'."\n";
    } else {
        echo "\nfunction spellClickHandler(editor, buttonId) {\n";
        echo "\teditor._textArea.value = editor.getHTML();\n";
        echo "\tvar speller = new spellChecker( editor._textArea );\n";
        echo "\tspeller.popUpUrl = \"" . $CFG->wwwroot ."lib/speller/spellchecker.html\";\n";
        echo "\tspeller.spellCheckScript = \"". $CFG->wwwroot ."lib/speller/server-scripts/spellchecker.php\";\n";
        echo "\tspeller._moogle_edit=1;\n";
        echo "\tspeller._editor=editor;\n";
        echo "\tspeller.openChecker();\n";
        echo '}'."\n";
    }
}

/**
 * Print button for spellchecking when editor is disabled
 */
function print_speller_button () {
    echo '<input type="button" value="Check spelling" onclick="openSpellChecker();" />'."\n";
}



/**
 * Adjust the list of allowed tags based on $CFG->allowobjectembed and user roles (admin)
 */
function adjust_allowed_tags() {

    global $CFG, $ALLOWED_TAGS;

    if (!empty($CFG->allowobjectembed)) {
        $ALLOWED_TAGS .= '<embed><object>';
    }
}


/**
 * This function makes the return value of ini_get consistent if you are
 * setting server directives through the .htaccess file in apache.
 * Current behavior for value set from php.ini On = 1, Off = [blank]
 * Current behavior for value set from .htaccess On = On, Off = Off
 * Contributed by jdell @ unr.edu
 *
 * @param string $ini_get_arg ?
 * @return boolean
 * @todo Finish documenting this function
 */
function ini_get_bool($ini_get_arg) {
    $temp = ini_get($ini_get_arg);
    
    if ($temp == '1' or strtolower($temp) == 'on') {
        return true;
    }
    return false;
}

/**
 * Generate and return a random string of the specified length.
 *
 * @param int $length The length of the string to be created.
 * @return string
 */
function random_string ($length=15) {
    $pool  = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $pool .= 'abcdefghijklmnopqrstuvwxyz';
    $pool .= '0123456789';
    $poollen = strlen($pool);
    mt_srand ((double) microtime() * 1000000);
    $string = '';
    for ($i = 0; $i < $length; $i++) {
        $string .= substr($pool, (mt_rand()%($poollen)), 1);
    }
    return $string;
}

// Fills user information
function init_user_var($user) {
    
    global $CFG;
    
    $user->loggedin = true;
    $user->site     = $CFG->wwwroot; // for added security, store the site in the session
    $user->sesskey  = random_string(10);
    $user->sessionIP = md5(getremoteaddr());   // Store the current IP in the session
    // backwards compatibility (TODO this will have to go eventually)
    fill_legacy_user_session($user);
    return $user;
    
}


// Authentication Function
// Returns true or false
function authenticate_account($username,$password) {
    
    global $CFG,$USER;
    
    if (empty($CFG->auth)) {
        $CFG->auth = 'internal';
    }
    if (!file_exists($CFG->dirroot . 'auth/' . $CFG->auth . '/lib.php')) {
        $CFG->auth = 'internal';
    }
    
    require_once($CFG->dirroot . 'auth/' . $CFG->auth . '/lib.php');

    // Module authentication function
    $function = $CFG->auth.'_authenticate_user_login';

    // Does the function exist
    if (!function_exists($function)) {
        print 'Error: function '.$function.' not found in auth/' . $CFG->auth . '/lib.php';
        return false;
    }
    
    if (!$user = $function($username,$password)) {
        return false;
    } 
    
    $ok = true;
    if (user_flag_get("banned", $user->ident)) { // this needs to change.
        $ok = false;
        $user = false;
        $USER = false;
        global $messages;
        $messages[] = __gettext("You have been banned from the system!");
        return false;
    }
    
    // Set Persistent Cookie
    $rememberme = optional_param('remember',0);
    if (!empty($rememberme)) {
        remember_login($user->ident);
    }
    
    
    $USER = init_user_var($user);
    return $ok;
}

// Attempts to get login from a cookie
function cookied_login() {
    global $USER;
    if((!empty($_COOKIE[AUTH_COOKIE])) && $ticket = md5($_COOKIE[AUTH_COOKIE])) {
        if ($user = get_record('users','code',$ticket)) {
            $USER = $user;
            
            /*** TODO: Create Proper Abstraction Interface - don't use file binding -- ugh ***/
            if (!user_flag_get("banned",$USER->ident)) {
                $USER = init_user_var($USER);
                return true;
            } else {
                global $messages;
                $messages[] = __gettext("You have been banned from the system!");
                return false;
            }
        }
    }
}

/**
 * elgg doesn't have a 'login' page yet, but it will so this can stay here for now
 */
function require_login() {
    global $USER, $SESSION,$FULLME;
    
    // Check to see if there's a persistent cookie
    cookied_login();
    
    // First check that the user is logged in to the site.
    if (empty($USER->loggedin) || $USER->site != $CFG->wwwroot) {
        $SESSION->wantsurl = $FULLME;
        if (!empty($_SERVER['HTTP_REFERER'])) {
            $SESSION->fromurl  = $_SERVER['HTTP_REFERER'];
        }
        $USER = NULL;
        redirect($CFG->wwwroot .'login/index.php');
        exit;
    }

    // Make sure current IP matches the one for this session (if required)
    if (!empty($CFG->tracksessionip)) {
        if ($USER->sessionIP != md5(getremoteaddr())) {
            error(__gettext('Sorry, but your IP number seems to have changed from when you first logged in.  This security feature prevents crackers stealing your identity while logged in to this site.  Normal users should not be seeing this message - please ask the site administrator for help.'));
        }
    }

    // Make sure the USER has a sesskey set up.  Used for checking script parameters.
    sesskey();

    return true;
}


function remember_login($id) {
    global $CFG;
    $id = (int) $id;
    if(!$id) return 0;
    
    // Double MD5
    if (!defined("SECRET_SALT")) {
        define("SECRET_SALT", "SECRET_SALT");
    }
    $ticket        = md5(SECRET_SALT . $id . time());
    $md5ticket = md5($ticket);
    
    // Update MD5 of authticket
    $user->code = $md5ticket;
    $user->ident = $id;
    update_record('users',$user);
    
    setcookie(AUTH_COOKIE, $ticket, time()+AUTH_COOKIE_LENGTH, $CFG->cookiepath);
    global $messages;
    $messages[] = __gettext("The system will remember you and automatically log you in next time.");
    
    return 1;
}

// Returns whether the user is logged in or not;
// if not logged in, checks for persistent cookie
function isloggedin() { 
    global $USER;
    if (empty($USER->ident) && empty($USER->loggedin)) {
        cookied_login();
    }
    return (!empty($USER->ident) && !empty($USER->loggedin));
}

function get_string($s) {
    return __gettext($s);
}

function print_header() {
    $args = func_get_args();
    echo  $args[0];
}

function print_footer() {
    $args = func_get_args();
    echo $args[0];
}

function clean_text($text, $format=FORMAT_MOODLE) {

    global $ALLOWED_TAGS;

    switch ($format) {
        case FORMAT_PLAIN:
            return $text;

        default:

        /// Remove tags that are not allowed
            $text = strip_tags($text, $ALLOWED_TAGS);
            
        /// Add some breaks into long strings of &nbsp;
            $text = preg_replace('/((&nbsp;){10})&nbsp;/', '\\1 ', $text);

        /// Clean up embedded scripts and , using kses
            $text = cleanAttributes($text);

        /// Remove script events
            $text = eregi_replace("([^a-z])language([[:space:]]*)=", "\\1Xlanguage=", $text);
            $text = eregi_replace("([^a-z])on([a-z]+)([[:space:]]*)=", "\\1Xon\\2=", $text);

            return $text;
    }
}

/**
 * Set a key in global configuration
 *
 * Set a key/value pair in both this session's {@link $CFG} global variable
 * and in the 'config' database table for future sessions.
 *
 * Can also be used to update keys for plugin-scoped configs in config_plugin table.
 * In that case it doesn't affect $CFG.
 *
 * @param string $name the key to set
 * @param string $value the value to set
 * @uses $CFG
 * @return bool
 */
function set_config($name, $value) {
/// No need for get_config because they are usually always available in $CFG

    global $CFG;

    $CFG->$name = $value;  // So it's defined for this invocation at least

    if (get_field('datalists', 'name', 'name', $name)) {
        return set_field('datalists', 'value', $value, 'name', $name);
    } else {
        $config->name = $name;
        $config->value = $value;
        return insert_record('datalists', $config);
    }
}

/**
 * Get configuration values from the global config table
 * or the config_plugins table.
 *
 * If called with no parameters it will do the right thing
 * generating $CFG safely from the database without overwriting
 * existing values.
 *
 * @param string $name
 * @uses $CFG
 * @return hash-like object or single value
 *
 */
function get_config($name=NULL) {

    global $CFG;

    if (!empty($name)) { // the user is asking for a specific value
        return get_record('datalists', 'name', $name);
    }

    // this was originally in setup.php
    if ($configs = get_records('datalists')) {
        $localcfg = (array)$CFG;
        foreach ($configs as $config) {
            if (!isset($localcfg[$config->name])) {
                $localcfg[$config->name] = $config->value;
            } else {
                if ($localcfg[$config->name] != $config->value ) {
                    // complain if the DB has a different
                    // value than config.php does
                    error_log("\$CFG->{$config->name} in config.php ({$localcfg[$config->name]}) overrides database setting ({$config->value})");
                }
            }
        }

        $localcfg = (object)$localcfg;
        return $localcfg;
    } else {
        // preserve $CFG if DB returns nothing or error
        return $CFG;
    }

}

function guest_user() {
    $user = new stdClass();

    $user->ident = 0;
    $user->username = '';
    $user->name = '';
    $user->email = '';
    $user->icon = -1;
    $user->icon_quota = 0;

    return $user;
}

function fill_legacy_user_session($user = NULL) {
    
    if (!$user || $user == NULL) {
        $user = guest_user();
    }
    
    /// Fills up all legacy user session data
    /// This function provides backward compatibility
    $_SESSION['userid'] = (int) $user->ident;
    $_SESSION['username'] = $user->username;
    $_SESSION['name'] = stripslashes($user->name);
    $_SESSION['email'] = stripslashes($user->email);
    $iconid = (int) $user->icon;
    if ($iconid == -1) {
        $_SESSION['icon'] = "default.png";
    } else {
        $icon = get_record('icons','ident',$iconid);
        $_SESSION['icon'] = $icon->filename;
    }
    $_SESSION['icon_quota'] = (int) $user->icon_quota;
}

/**
 * Replace function htmlspecialchars_decode()
 *
 * @category    PHP
 * @package     PHP_Compat
 * @link        http://php.net/function.htmlspecialchars_decode
 * @author      Aidan Lister <aidan@php.net>
 * @version     $Revision: 1.3 $
 * @since       PHP 5.1.0
 * @require     PHP 4.0.0 (user_error)
 */
if (!function_exists('htmlspecialchars_decode')) {
    function htmlspecialchars_decode($string, $quote_style = null)
    {
        // Sanity check
        if (!is_scalar($string)) {
            user_error('htmlspecialchars_decode() expects parameter 1 to be string, ' .
                gettype($string) . ' given', E_USER_WARNING);
            return;
        }

        if (!is_int($quote_style) && $quote_style !== null) {
            user_error('htmlspecialchars_decode() expects parameter 2 to be integer, ' .
                gettype($quote_style) . ' given', E_USER_WARNING);
            return;
        }

        // Init
        $from   = array('&amp;', '&lt;', '&gt;');
        $to     = array('&', '<', '>');
        
        // The function does not behave as documented
        // This matches the actual behaviour of the function
        if ($quote_style & ENT_COMPAT || $quote_style & ENT_QUOTES) {
            $from[] = '&quot;';
            $to[]   = '"';
            
            $from[] = '&#039;';
            $to[]   = "'";
        }

        return str_replace($from, $to, $string);
    }
}


/**
 * Returns the maximum size for uploading files.
 *
 * There are five possible upload limits:
 * 1. in Apache using LimitRequestBody (no way of checking or changing this)
 * 2. in php.ini for 'upload_max_filesize' (can not be changed inside PHP)
 * 3. in .htaccess for 'upload_max_filesize' (can not be changed inside PHP)
 * 4. in php.ini for 'post_max_size' (can not be changed inside PHP)
 * 5. by the limitations on the current situation (eg file quota)
 *
 * The last one is passed to this function as an argument (in bytes).
 * Anything defined as 0 is ignored.
 * The smallest of all the non-zero numbers is returned.
 *
 * @param int $maxbytes Current maxbytes (in bytes)
 * @return int The maximum size for uploading files.
 * @todo Finish documenting this function
 */
function get_max_upload_file_size($maxbytes=0) {

    if (! $filesize = ini_get('upload_max_filesize')) {
        $filesize = '5M';
    }
    $minimumsize = get_real_size($filesize);

    if ($postsize = ini_get('post_max_size')) {
        $postsize = get_real_size($postsize);
        if ($postsize < $minimumsize) {
            $minimumsize = $postsize;
        }
    }

    if ($maxbytes and $maxbytes < $minimumsize) {
        $minimumsize = $maxbytes;
    }

    return $minimumsize;
}

function remove_dir($dir, $content_only=false) {
    // if content_only=true then delete all but
    // the directory itself

    $handle = opendir($dir);
    while (false!==($item = readdir($handle))) {
        if($item != '.' && $item != '..') {
            if(is_dir($dir.'/'.$item)) {
                remove_dir($dir.'/'.$item);
            }else{
                unlink($dir.'/'.$item);
            }
        }
    }
    closedir($handle);
    if ($content_only) { 
        return true;
    }
    return rmdir($dir);
}

//Function to check if a directory exists
//and, optionally, create it
function check_dir_exists($dir,$create=false) {
    
    global $CFG;
    
    $status = true;
    if(!is_dir($dir)) {
        if (!$create) {
            $status = false;
        } else {
            umask(0000);
            $status = mkdir ($dir,$CFG->directorypermissions);
        }
    }
    return $status;
}

// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
// ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//This functions are used to copy any file or directory ($from_file)
//to a new file or directory ($to_file). It works recursively and
//mantains file perms.
//I've copied it from: http://www.php.net/manual/en/function.copy.php
//Little modifications done

function copy_file ($from_file,$to_file) {
    
    global $CFG;
    
    if (is_file($from_file)) {
        umask(0000);
        if (copy($from_file,$to_file)) {
            chmod($to_file,$CFG->filepermissions);
            return true;
        }
        return false;
    }
    else if (is_dir($from_file)) {
        return copy_dir($from_file,$to_file);
    }
    else{
        return false;
    }
}

function copy_dir($from_file,$to_file) {
    
    global $CFG;
    
    if (!is_dir($to_file)) {
        umask(0000);
        $status = mkdir($to_file,$CFG->directorypermissions);
    }
    $dir = opendir($from_file);
    while ($file=readdir($dir)) {
        if ($file=="." || $file=="..") {
            continue;
        }
        $status = copy_file ("$from_file/$file","$to_file/$file");
    }
    closedir($dir);
    return $status;
}


function zip_files ($originalfiles, $destination) {
//Zip an array of files/dirs to a destination zip file
//Both parameters must be FULL paths to the files/dirs

    global $CFG;

    //Extract everything from destination
    $path_parts = pathinfo(cleardoubleslashes($destination));
    $destpath = $path_parts["dirname"];       //The path of the zip file
    $destfilename = $path_parts["basename"];  //The name of the zip file
    $extension = $path_parts["extension"];    //The extension of the file

    //If no file, error
    if (empty($destfilename)) {
        return false;
    }

    //If no extension, add it
    if (empty($extension)) {
        $extension = 'zip';
        $destfilename = $destfilename.'.'.$extension;
    }

    //Check destination path exists
    if (!is_dir($destpath)) {
        return false;
    }

    //Check destination path is writable. TODO!!

    //Clean destination filename
    $destfilename = clean_filename($destfilename);

    //Now check and prepare every file
    $files = array();
    $origpath = NULL;

    foreach ($originalfiles as $file) {  //Iterate over each file
        //Check for every file
        $tempfile = cleardoubleslashes($file); // no doubleslashes!
        //Calculate the base path for all files if it isn't set
        if ($origpath === NULL) {
            $origpath = rtrim(cleardoubleslashes(dirname($tempfile)), "/");
        }
        //See if the file is readable
        if (!is_readable($tempfile)) {  //Is readable
            continue;
        }
        //See if the file/dir is in the same directory than the rest
        if (rtrim(cleardoubleslashes(dirname($tempfile)), "/") != $origpath) {
            continue;
        }
        //Add the file to the array
        $files[] = $tempfile;
    }

    //Everything is ready:
    //    -$origpath is the path where ALL the files to be compressed reside (dir).
    //    -$destpath is the destination path where the zip file will go (dir).
    //    -$files is an array of files/dirs to compress (fullpath)
    //    -$destfilename is the name of the zip file (without path)

    //print_object($files);                  //Debug

    if (empty($CFG->zip)) {    // Use built-in php-based zip function

        include_once("$CFG->libdir/pclzip/pclzip.lib.php");
        $archive = new PclZip(cleardoubleslashes("$destpath/$destfilename"));
        if (($list = $archive->create($files, PCLZIP_OPT_REMOVE_PATH,$origpath) == 0)) {
            notice($archive->errorInfo(true));
            return false;
        }

    } else {                   // Use external zip program

        $filestozip = "";
        foreach ($files as $filetozip) {
            $filestozip .= escapeshellarg(basename($filetozip));
            $filestozip .= " ";
        }
        //Construct the command
        $separator = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? ' &' : ' ;';
        $command = 'cd '.escapeshellarg($origpath).$separator.
                    escapeshellarg($CFG->zip).' -r '.
                    escapeshellarg(cleardoubleslashes("$destpath/$destfilename")).' '.$filestozip;
        //All converted to backslashes in WIN
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $command = str_replace('/','\\',$command);
        }
        Exec($command);
    }
    return true;
}

function unzip_file ($zipfile, $destination = '', $showstatus = true) {
//Unzip one zip file to a destination dir
//Both parameters must be FULL paths
//If destination isn't specified, it will be the
//SAME directory where the zip file resides.

    global $CFG;

    //Extract everything from zipfile
    $path_parts = pathinfo(cleardoubleslashes($zipfile));
    $zippath = $path_parts["dirname"];       //The path of the zip file
    $zipfilename = $path_parts["basename"];  //The name of the zip file
    $extension = $path_parts["extension"];    //The extension of the file

    //If no file, error
    if (empty($zipfilename)) {
        return false;
    }

    //If no extension, error
    if (empty($extension)) {
        return false;
    }

    //Clear $zipfile
    $zipfile = cleardoubleslashes($zipfile);

    //Check zipfile exists
    if (!file_exists($zipfile)) {
        return false;
    }

    //If no destination, passed let's go with the same directory
    if (empty($destination)) {
        $destination = $zippath;
    }

    //Clear $destination
    $destpath = rtrim(cleardoubleslashes($destination), "/");

    //Check destination path exists
    if (!is_dir($destpath)) {
        return false;
    }

    //Check destination path is writable. TODO!!

    //Everything is ready:
    //    -$zippath is the path where the zip file resides (dir)
    //    -$zipfilename is the name of the zip file (without path)
    //    -$destpath is the destination path where the zip file will uncompressed (dir)

    if (empty($CFG->unzip)) {    // Use built-in php-based unzip function

        include_once("$CFG->libdir/pclzip/pclzip.lib.php");
        $archive = new PclZip(cleardoubleslashes("$zippath/$zipfilename"));
        if (!$list = $archive->extract(PCLZIP_OPT_PATH, $destpath,
                                       PCLZIP_CB_PRE_EXTRACT, 'unzip_cleanfilename')) {
            notice($archive->errorInfo(true));
            return false;
        }

    } else {                     // Use external unzip program

        $separator = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? ' &' : ' ;';
        $redirection = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? '' : ' 2>&1';

        $command = 'cd '.escapeshellarg($zippath).$separator.
                    escapeshellarg($CFG->unzip).' -o '.
                    escapeshellarg(cleardoubleslashes("$zippath/$zipfilename")).' -d '.
                    escapeshellarg($destpath).$redirection;
        //All converted to backslashes in WIN
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $command = str_replace('/','\\',$command);
        }
        Exec($command,$list);
    }

    //Display some info about the unzip execution
    if ($showstatus) {
        unzip_show_status($list,$destpath);
    }

    return true;
}

function unzip_cleanfilename ($p_event, &$p_header) {
//This function is used as callback in unzip_file() function
//to clean illegal characters for given platform and to prevent directory traversal.
//Produces the same result as info-zip unzip.
    $p_header['filename'] = ereg_replace('[[:cntrl:]]', '', $p_header['filename']); //strip control chars first!
    $p_header['filename'] = ereg_replace('\.\.+', '', $p_header['filename']); //directory traversal protection
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        $p_header['filename'] = ereg_replace('[:*"?<>|]', '_', $p_header['filename']); //replace illegal chars
        $p_header['filename'] = ereg_replace('^([a-zA-Z])_', '\1:', $p_header['filename']); //repair drive letter
    } else {
        //Add filtering for other systems here
        // BSD: none (tested)
        // Linux: ??
        // MacosX: ??
    }
    $p_header['filename'] = cleardoubleslashes($p_header['filename']); //normalize the slashes/backslashes
    return 1;
}


function unzip_show_status ($list,$removepath) {
//This function shows the results of the unzip execution
//depending of the value of the $CFG->zip, results will be
//text or an array of files.

    global $CFG;

    if (empty($CFG->unzip)) {    // Use built-in php-based zip function
        $strname = get_string("name");
        $strsize = get_string("size");
        $strmodified = get_string("modified");
        $strstatus = get_string("status");
        echo "<table cellpadding=\"4\" cellspacing=\"2\" border=\"0\" width=\"640\">";
        echo "<tr><th class=\"header\" align=\"left\">$strname</th>";
        echo "<th class=\"header\" align=\"right\">$strsize</th>";
        echo "<th class=\"header\" align=\"right\">$strmodified</th>";
        echo "<th class=\"header\" align=\"right\">$strstatus</th></tr>";
        foreach ($list as $item) {
            echo "<tr>";
            $item['filename'] = str_replace(cleardoubleslashes($removepath).'/', "", $item['filename']);
            print_cell("left", $item['filename']);
            if (! $item['folder']) {
                print_cell("right", display_size($item['size']));
            } else {
                echo "<td>&nbsp;</td>";
            }
            $filedate  = userdate($item['mtime'], get_string("strftimedatetime"));
            print_cell("right", $filedate);
            print_cell("right", $item['status']);
            echo "</tr>";
        }
        echo "</table>";

    } else {                   // Use external zip program
        print_simple_box_start("center");
        echo "<pre>";
        foreach ($list as $item) {
            echo str_replace(cleardoubleslashes($removepath.'/'), '', $item).'<br />';
        }
        echo "</pre>";
        print_simple_box_end();
    }
}

function isadmin($userid=0) {
    global $USER;

    static $admins, $nonadmins;
    
    if (!isset($admins)) {
        $admins = array();
        $nonadmins = array();
    }

    if (empty($userid)) {
        if (empty($USER)) { // maybe not logged in
            return false;
        } else {
            $userid = (int) $USER->ident;
        }
    }

    if (in_array($userid, $admins)) {
        return true;
    } else if (in_array($userid, $nonadmins)) {
        return false;
    } else if (user_flag_get('admin', $userid)) {
        $admins[] = $userid;
        return true;
    } else {
        $nonadmins[] = $userid;
        return false;
    }

}



function get_admins() {
    global $CFG;
    return get_records_sql('SELECT u.* FROM '.$CFG->prefix.'users u
                            JOIN '.$CFG->prefix.'user_flags uf ON u.ident = uf.user_id
                            WHERE flag = ?',array('admin'));

}

function get_admin() {
    global $CFG;
    return get_record_sql('SELECT u.* FROM '.$CFG->prefix.'users u
                            JOIN '.$CFG->prefix.'user_flags uf ON u.ident = uf.user_id
                            WHERE flag = ? ORDER BY ident',array('admin'),true);


}

// cli_die($str) - a perlish die()
//
// this function call will exit with a warning and an exit code
// that clearly indicates that something went wrong.
//
// We shouldn't need this, but due to PHP's web-centric heritage,
// die()/exit() cant print a warnign _and_ set a non-success exit
// code. Silly thing -- disregarding POSIX and friends doesn't get
// you very far ;-D 
function cli_die ($str, $code) {
    trigger_error($str);
    exit(1);

}



// Take a comma-separated string of keywords and create the relevant tag entries
// in the database. Returns a cleaned comma-separated keyword string.
function insert_tags_from_string ($string, $tagtype, $ref, $access, $owner) {

    $ref = (int) $ref;
    $owner = (int) $owner;
    $tagtype = trim($tagtype);
    $access = trim($access);
    $string = trim($string);
    $keywords = "";

    $string = str_replace("\n", "", $string);
    $string = str_replace("\r", "", $string);
    if ($string) {
        $keyword_list = explode(",", $string);
        $keyword_list = array_unique($keyword_list);
        sort($keyword_list);
        if (sizeof($keyword_list) > 0) {
            foreach($keyword_list as $key => $list_item) {
                $list_item = trim($list_item);
                if ($list_item) {
                    if ($key > 0) {
                        $keywords .= ", ";
                    }
                    $keywords .= $list_item;
                    $t = new StdClass;
                    $t->tagtype = $tagtype;
                    $t->access = $access;
                    $t->tag = $list_item;
                    $t->ref = $ref;
                    $t->owner = $owner;
                    insert_record('tags', $t);
                }
            }
        }
    }

    return($keywords);
}


// return the "this is restricted" text for a given access value
// functionised to reduce code duplication
function get_access_description ($accessvalue) {
    
    if ($accessvalue != "PUBLIC") {
        if ($accessvalue == "LOGGED_IN") {
            $title = "[" . __gettext("Logged in users") . "] ";
        } else if (substr_count($accessvalue, "user") > 0) {
            $title = "[" . __gettext("Private") . "] ";
        } else {
            $title = "[" . __gettext("Restricted") . "] ";
        }
    } else {
        $title = "";
    }
    
    return $title;
}

// Activate URLs - turns any URLs found into links
function activate_urls ($str) {
    
    // Function for URL autodiscovery
    
    $search = array();
    $replace = array();

    // lift all links, images and image maps
    $url_tags = array (
                      "'<a[^>]*>.*?</a>'si",
                      "'<map[^>]*>.*?</map>'si",
                      "'<script[^>]*>.*?</script>'si",
                      "'<style[^>]*>.*?</style>'si",
                      "'<[^>]+>'si"
                      );
    
    foreach($url_tags as $url_tag)
    {
        preg_match_all($url_tag, $str, $matches, PREG_SET_ORDER);
        foreach($matches as $match)
        {
            $key = "<" . md5($match[0]) . ">";
            $search[] = $key;
            $replace[] = $match[0];
        }
    }
    
    $str = str_replace($replace, $search, $str);
    
    // indicate where urls end if they have these trailing special chars
    $sentinals = array("/&(quot|#34);/i",    // Replace html entities
                        "/&(lt|#60);/i",
                        "/&(gt|#62);/i",
                        "/&(nbsp|#160);/i",
                        "/&(iexcl|#161);/i",
                        "/&(cent|#162);/i",
                        "/&(pound|#163);/i",
                        "/&(copy|#169);/i");
    
    $str = preg_replace($sentinals, "<marker>\\0", $str);
    
    // URL into links
    $str =
        preg_replace( "|\w{3,10}://[\w\.\-_]+(:\d+)?[^\s\"\'<>\(\)\{\}]*|", 
        "<a href=\"\\0\">[".__gettext("Click to view link") . "]</a>", $str );
    
    $str = str_replace("<marker>", '', $str);
    return str_replace($search, $replace, $str);
    
}


?>
