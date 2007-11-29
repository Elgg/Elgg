<?php

/**
 * uploadlib.php - This class handles all aspects of fileuploading
 *
 * @author Penny Leach
 * @version 1.5
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 * @package moodlecore
 */


/**
 * This class handles all aspects of fileuploading
 */
class upload_manager {

   /**
    * Array to hold local copies of stuff in $_FILES
    * @var array $files 
    */
    var $files;
   /**
    * Holds all configuration stuff
    * @var array $config 
    */
    var $config;
   /**
    * Keep track of if we're ok
    * (errors for each file are kept in $files['whatever']['uploadlog']
    * @var boolean $status
    */
    var $status;
   /**
    * If we're only getting one file.
    * (for logging and virus notifications)
    * @var string $inputname 
    */
    var $inputname;
   /**
    * If we're given silent=true in the constructor, this gets built 
    * up to hold info about the process.
    * @var string $notify 
    */
    var $notify;

    /**
     * Constructor, sets up configuration stuff so we know how to act.
     *
     * Note: destination not taken as parameter as some modules want to use the insertid in the path and we need to check the other stuff first.
     *
     * @uses $CFG
     * @param string $inputname If this is given the upload manager will only process the file in $_FILES with this name.
     * @param boolean $deleteothers Whether to delete other files in the destination directory (optional, defaults to false)
     * @param boolean $handlecollisions Whether to use {@link handle_filename_collision()} or not. (optional, defaults to false)
     * @param boolean $recoverifmultiple If we come across a virus, or if a file doesn't validate or whatever, do we continue? optional, defaults to true.
     * @param int $maxbytes max bytes for this file {@link get_max_upload_file_size()}.
     * @param boolean $silent Whether to notify errors or not.
     * @param boolean $allownull Whether we care if there's no file when we've set the input name.
     * @param boolean $allownullmultiple Whether we care if there's no files AT ALL  when we've got multiples. This won't complain if we have file 1 and file 3 but not file 2, only for NO FILES AT ALL.
     */
    function upload_manager($inputname='', $deleteothers=false, $handlecollisions=false, $recoverifmultiple=false, $maxbytes=0, $silent=false, $allownull=false, $allownullmultiple=true) {
        
        global $CFG;
        
        $this->config->deleteothers = $deleteothers;
        $this->config->handlecollisions = $handlecollisions;
        $this->config->recoverifmultiple = $recoverifmultiple;
        $this->config->maxbytes = get_max_upload_file_size($maxbytes);
        $this->config->silent = $silent;
        $this->config->allownull = $allownull;
        $this->files = array();
        $this->status = false; 
        $this->inputname = $inputname;
        if (empty($this->inputname)) {
            $this->config->allownull = $allownullmultiple;
        }
    }
    
    /** 
     * Gets all entries out of $_FILES and stores them locally in $files and then
     * checks each one against {@link get_max_upload_file_size()} and calls {@link cleanfilename()} 
     * and scans them for viruses etc.
     * @uses $CFG
     * @uses $_FILES
     * @return boolean
     */
    function preprocess_files() {
        global $CFG;

        foreach ($_FILES as $name => $file) {
            $this->status = true; // only set it to true here so that we can check if this function has been called.
            if (empty($this->inputname) || $name == $this->inputname) { // if we have input name, only process if it matches.
                $file['originalname'] = $file['name']; // do this first for the log.
                $this->files[$name] = $file; // put it in first so we can get uploadlog out in print_upload_log.
                $this->files[$name]['uploadlog'] = '';
                $this->status = $this->validate_file($this->files[$name]); // default to only allowing empty on multiple uploads.
                if (!$this->status && ($this->files[$name]['error'] == 0 || $this->files[$name]['error'] == 4) && ($this->config->allownull || empty($this->inputname))) {
                    // this shouldn't cause everything to stop.. modules should be responsible for knowing which if any are compulsory.
                    continue; 
                }
                if ($this->status && !empty($CFG->runclamonupload)) {
                    $this->status = clam_scan_file($this->files[$name]);
                }
                if (!$this->status) {
                    if (!$this->config->recoverifmultiple && count($this->files) > 1) {
                        $a->name = $this->files[$name]['originalname'];
                        $a->problem = $this->files[$name]['uploadlog'];
                        $msg = sprintf(__gettext('Your file upload has failed because there was a problem with one of the files, %s.<br /> Here is a log of the problems:<br />%s<br />Not recovering.'), $a->name, $a->problem);
                        if (!$this->config->silent) {
                            notify($msg);
                        }
                        else {
                            $this->notify .= '<br />'. $msg;
                        }
                        $this->status = false;
                        return false;

                    } else if (count($this->files) == 1) {

                        if (!$this->config->silent and !$this->config->allownull) {
                            notify($this->files[$name]['uploadlog']);
                        } else {
                            $this->notify .= '<br />'. $this->files[$name]['uploadlog'];
                        }
                        $this->status = false;
                        return false;
                    }
                }
                else {
                    $newname = clean_filename($this->files[$name]['name']);
                    if ($newname != $this->files[$name]['name']) {
                        $a->oldname = $this->files[$name]['name'];
                        $a->newname = $newname;
                        $this->files[$name]['uploadlog'] .= sprintf(__gettext('File was renamed from %s to %s because of invalid characters.'), $a->oldname, $a->newname);
                    }
                    $this->files[$name]['name'] = $newname;
                    $this->files[$name]['clear'] = true; // ok to save.
                }
            }
        }
        if (!is_array($_FILES) || count($_FILES) == 0) {
            return $this->config->allownull;
        }
        $this->status = true;
        return true; // if we've got this far it means that we're recovering so we want status to be ok.
    }

    /**
     * Validates a single file entry from _FILES
     *
     * @param object $file The entry from _FILES to validate
     * @return boolean True if ok.
     */
    function validate_file(&$file) {
        if (empty($file)) {
            return false;
        }
        if (!is_uploaded_file($file['tmp_name']) || $file['size'] == 0) {
            $file['uploadlog'] .= "\n".$this->get_file_upload_error($file);
            return false;
        }
        if ($file['size'] > $this->config->maxbytes) {
            $file['uploadlog'] .= "\n". sprintf(__gettext('Sorry, but that file is too big (limit is %s)'), display_size($this->config->maxbytes));
            return false;
        }
        return true;
    }

    /** 
     * Moves all the files to the destination directory.
     *
     * @uses $CFG
     * @uses $USER
     * @param string $destination The destination directory.
     * @return boolean status;
     */
    function save_files($destination) {
        global $CFG, $USER;
        $textlib = textlib_get_instance();
        
        if (!$this->status) { // preprocess_files hasn't been run
            $this->preprocess_files();
        }
        if ($this->status) {
            if (!($textlib->strpos($destination, $CFG->dataroot) === false)) {
                // take it out for giving to make_upload_directory
                $destination = $textlib->substr($destination, $textlib->strlen($CFG->dataroot));
            }

            if ($destination{$textlib->strlen($destination)-1} == '/') { // strip off a trailing / if we have one
                $destination = $textlib->substr($destination, 0, -1);
            }

            if (!make_upload_directory($destination, true)) { //TODO maybe put this function here instead of moodlelib.php now.
                $this->status = false;
                return false;
            }
            
            $destination = $CFG->dataroot . $destination; // now add it back in so we have a full path

            $exceptions = array(); //need this later if we're deleting other files.

            foreach (array_keys($this->files) as $i) {

                if (!$this->files[$i]['clear']) {
                    // not ok to save
                    continue;
                }

                if ($this->config->handlecollisions) {
                    $this->handle_filename_collision($destination, $this->files[$i]);
                }
                if (move_uploaded_file($this->files[$i]['tmp_name'], $destination.'/'.$this->files[$i]['name'])) {
                    chmod($destination .'/'. $this->files[$i]['name'], $CFG->filepermissions);
                    $this->files[$i]['fullpath'] = $destination.'/'.$this->files[$i]['name'];
                    $this->files[$i]['uploadlog'] .= "\n".__gettext('File uploaded successfully');
                    $this->files[$i]['saved'] = true;
                    $exceptions[] = $this->files[$i]['name'];
                    // now add it to the log (this is important so we know who to notify if a virus is found later on)
                    clam_log_upload($this->files[$i]['fullpath']);
                    $savedsomething=true;
                }
            }
            if (!empty($savedsomething) && $this->config->deleteothers) {
                $this->delete_other_files($destination, $exceptions);
            }
        }
        if (empty($savedsomething)) {
            $this->status = false;
            if ((empty($this->config->allownull) && !empty($this->inputname)) || (empty($this->inputname) && empty($this->config->allownullmultiple))) {
                notify(__gettext('No file was found - are you sure you selected one to upload?'));
            }
            return false;
        }
        return $this->status;
    }
    
    /**
     * Wrapper function that calls {@link preprocess_files()} and {@link viruscheck_files()} and then {@link save_files()}
     * Modules that require the insert id in the filepath should not use this and call these functions seperately in the required order.
     * @parameter string $destination Where to save the uploaded files to.
     * @return boolean
     */ 
    function process_file_uploads($destination) {
        if ($this->preprocess_files()) {
            return $this->save_files($destination);
        }
        return false;
    }

    /** 
     * Deletes all the files in a given directory except for the files in $exceptions (full paths)
     *
     * @param string $destination The directory to clean up.
     * @param array $exceptions Full paths of files to KEEP.
     */
    function delete_other_files($destination, $exceptions=null) {
        if ($filestodel = get_directory_list($destination)) {
            foreach ($filestodel as $file) {
                if (!is_array($exceptions) || !in_array($file, $exceptions)) {
                    unlink($destination .'/'. $file);
                    $deletedsomething = true;
                }
            }
        }
        if ($deletedsomething) {
            $msg = __gettext('The old file(s) in your upload area have been deleted');
            if (!$this->config->silent) {
                notify($msg);
            }
            else {
                $this->notify .= '<br />'. $msg;
            }
        }
    }
    
    /**
     * Handles filename collisions - if the desired filename exists it will rename it according to the pattern in $format
     * @param string $destination Destination directory (to check existing files against)
     * @param object $file Passed in by reference. The current file from $files we're processing.
     * @param string $format The printf style format to rename the file to (defaults to filename_number.extn)
     * @return string The new filename.
     * @todo verify return type - this function does not appear to return anything since $file is passed in by reference
     */
    function handle_filename_collision($destination, &$file, $format='%s_%d.%s') {
        $bits = explode('.', $file['name']);
        // check for collisions and append a nice numberydoo.
        if (file_exists($destination .'/'. $file['name'])) {
            $a->oldname = $file['name'];
            for ($i = 1; true; $i++) {
                $try = sprintf($format, $bits[0], $i, $bits[1]);
                if ($this->check_before_renaming($destination, $try, $file)) {
                    $file['name'] = $try;
                    break;
                }
            }
            $a->newname = $file['name'];
            $file['uploadlog'] .= "\n". sprintf(__gettext('File was renamed from %s to %s because there was a filename conflict.'), $a->oldname, $a->newname);
        }
    }
    
    /**
     * This function checks a potential filename against what's on the filesystem already and what's been saved already.
     * @param string $destination Destination directory (to check existing files against)
     * @param string $nametocheck The filename to be compared.
     * @param object $file The current file from $files we're processing.
     * return boolean
     */
    function check_before_renaming($destination, $nametocheck, $file) {
        if (!file_exists($destination .'/'. $nametocheck)) {
            return true;
        }
        if ($this->config->deleteothers) {
            foreach ($this->files as $tocheck) {
                // if we're deleting files anyway, it's not THIS file and we care about it and it has the same name and has already been saved..
                if ($file['tmp_name'] != $tocheck['tmp_name'] && $tocheck['clear'] && $nametocheck == $tocheck['name'] && $tocheck['saved']) {
                    $collision = true;
                }
            }
            if (!$collision) {
                return true;
            }
        }
        return false;
    }

    /**
     * ?
     *
     * @param object $file Passed in by reference. The current file from $files we're processing.
     * @return string
     * @todo Finish documenting this function
     */
    function get_file_upload_error(&$file) {
        
        switch ($file['error']) {
        case 0: // UPLOAD_ERR_OK
            if ($file['size'] > 0) {
                $errmessage = sprintf(__gettext('An unknown problem occurred while uploading the file \'%s\' (perhaps it was too large?)'), $file['name']);
            } else {
                $errmessage = __gettext('No file was found - are you sure you selected one to upload?'); /// probably a dud file name
            }
            break;
            
        case 1: // UPLOAD_ERR_INI_SIZE
            $errmessage = __gettext('Uploaded file exceeded the maximum size limit set by the server');
            break;
            
        case 2: // UPLOAD_ERR_FORM_SIZE
            $errmessage = __gettext('Uploaded file exceeded the maximum size limit set by the form');
            break;
            
        case 3: // UPLOAD_ERR_PARTIAL
            $errmessage = __gettext('File was only partially uploaded');
            break;
            
        case 4: // UPLOAD_ERR_NO_FILE
            $errmessage = __gettext('No file was found - are you sure you selected one to upload?');
            break;
            
        default:
            $errmessage = sprintf(__gettext('An unknown problem occurred while uploading the file \'%s\' (perhaps it was too large?)'), $file['name']);
        }
        return $errmessage;
    }
    
    /**
     * prints a log of everything that happened (of interest) to each file in _FILES
     * @param $return - optional, defaults to false (log is echoed)
     */
    function print_upload_log($return=false,$skipemptyifmultiple=false) {
        foreach (array_keys($this->files) as $i => $key) {
            if (count($this->files) > 1 && !empty($skipemptyifmultiple) && $this->files[$key]['error'] == 4) {
                continue;
            }
            $str .= '<strong>'. sprintf(__gettext('Upload log for file %u'), $i+1) .' '
                .((!empty($this->files[$key]['originalname'])) ? '('.$this->files[$key]['originalname'].')' : '')
                .'</strong> :'. nl2br($this->files[$key]['uploadlog']) .'<br />';
        }
        if ($return) {
            return $str;
        }
        echo $str;
    }

    /**
     * If we're only handling one file (if inputname was given in the constructor) this will return the (possibly changed) filename of the file.
     @return boolean
     */
    function get_new_filename() {
        if (!empty($this->inputname) && count($this->files) == 1) {
            return $this->files[$this->inputname]['name'];
        }
        return false;
    }

    /** 
     * If we're only handling one file (if input name was given in the constructor) this will return the full path to the saved file.
     * @return boolean
     */
    function get_new_filepath() {
        if (!empty($this->inputname) && count($this->files) == 1) {
            return $this->files[$this->inputname]['fullpath'];
        }
        return false;
    }

    /** 
     * If we're only handling one file (if inputname was given in the constructor) this will return the ORIGINAL filename of the file.
     * @return boolean
     */
    function get_original_filename() {
        if (!empty($this->inputname) && count($this->files) == 1) {
            return $this->files[$this->inputname]['originalname'];
        }
        return false;
    }

    /**
     * If we're only handling on file (if inputname was given in the constructor) this will return the size of the file.
     */
    function get_filesize() {
        if (!empty($this->inputname) && count($this->files) == 1) {
            return $this->files[$this->inputname]['size'];
        }
    }


    /** 
     * This function returns any errors wrapped up in red.
     * @return string
     */
    function get_errors() {
        return $this->notify ;
    }
}

/**************************************************************************************
THESE FUNCTIONS ARE OUTSIDE THE CLASS BECAUSE THEY NEED TO BE CALLED FROM OTHER PLACES.
FOR EXAMPLE CLAM_HANDLE_INFECTED_FILE AND CLAM_REPLACE_INFECTED_FILE USED FROM CRON
UPLOAD_PRINT_FORM_FRAGMENT DOESN'T REALLY BELONG IN THE CLASS BUT CERTAINLY IN THIS FILE
***************************************************************************************/


/**
 * This function prints out a number of upload form elements.
 *
 * @param int $numfiles The number of elements required (optional, defaults to 1)
 * @param array $names Array of element names to use (optional, defaults to FILE_n)
 * @param array $descriptions Array of strings to be printed out before each file bit.
 * @param boolean $uselabels -Whether to output text fields for file descriptions or not (optional, defaults to false)
 * @param array $labelnames Array of element names to use for labels (optional, defaults to LABEL_n)
 * @param int $maxbytes used to calculate upload max size ( using {@link get_max_upload_file_size})
 * @param boolean $return -Whether to return the string (defaults to false - string is echoed)
 * @return string Form returned as string if $return is true
 */ 
function upload_print_form_fragment($numfiles=1, $names=null, $descriptions=null, $uselabels=false, $labelnames=null, $maxbytes=0, $return=false) {
    global $CFG;
    $maxbytes = get_max_upload_file_size($CFG->maxbytes, $coursebytes, $maxbytes);
    $str = '<input type="hidden" name="MAX_FILE_SIZE" value="'. $maxbytes .'" />'."\n";
    for ($i = 0; $i < $numfiles; $i++) {
        if (is_array($descriptions) && !empty($descriptions[$i])) {
            $str .= '<strong>'. $descriptions[$i] .'</strong><br />';
        }
        $name = ((is_array($names) && !empty($names[$i])) ? $names[$i] : 'FILE_'.$i);
        $str .= '<input type="file" size="50" name="'. $name .'" alt="'. $name .'" /><br />'."\n";
        if ($uselabels) {
            $lname = ((is_array($labelnames) && !empty($labelnames[$i])) ? $labelnames[$i] : 'LABEL_'.$i);
            $str .= __gettext('Title:').' <input type="text" size="50" name="'. $lname .'" alt="'. $lname
                .'" /><br /><br />'."\n";
        }
    }
    if ($return) {
        return $str;
    }
    else {
        echo $str;
    }
}


/**
 * Deals with an infected file - either moves it to a quarantinedir 
 * (specified in CFG->quarantinedir) or deletes it.
 *
 * If moving it fails, it deletes it.
 *
 *@uses $CFG
 * @uses $USER
 * @param string $file Full path to the file
 * @param int $userid If not used, defaults to $USER->id (there in case called from cron)
 * @param boolean $basiconly Admin level reporting or user level reporting.
 * @return string Details of what the function did.
 */
function clam_handle_infected_file($file, $userid=0, $basiconly=false) {
    
    global $CFG, $USER;
    if ($USER && !$userid) {
        $userid = $USER->ident;
    }
    $delete = true;
    if (file_exists($CFG->quarantinedir) && is_dir($CFG->quarantinedir) && is_writable($CFG->quarantinedir)) {
        $now = date('YmdHis');
        if (rename($file, $CFG->quarantinedir .'/'. $now .'-user-'. $userid .'-infected')) { 
            $delete = false;
            clam_log_infected($file, $CFG->quarantinedir.'/'. $now .'-user-'. $userid .'-infected', $userid);
            if ($basiconly) {
                $notice .= "\n". __gettext('The file has been moved to a quarantine directory.');
            }
            else {
                $notice .= "\n". sprintf(__gettext('The file has been moved to your specified quarantine directory, the new location is %s'), $CFG->quarantinedir.'/'. $now .'-user-'. $userid .'-infected');
            }
        }
        else {
            if ($basiconly) {
                $notice .= "\n". __gettext('The file has been deleted');
            }
            else {
                $notice .= "\n". sprintf(__gettext('Could not move the file into your specified quarantine directory, %s. You need to fix this as files are being deleted if they\'re found to be infected.'), $CFG->quarantinedir);
            }
        }
    }
    else {
        if ($basiconly) {
            $notice .= "\n". __gettext('The file has been deleted');
        }
        else {
            $notice .= "\n". sprintf(__gettext('Could not move the file into your specified quarantine directory, %s. You need to fix this as files are being deleted if they\'re found to be infected.'), $CFG->quarantinedir);
        }
    }
    if ($delete) {
        if (unlink($file)) {
            clam_log_infected($file, '', $userid);
            $notice .= "\n". __gettext('The file has been deleted');
        }
        else {
            if ($basiconly) {
                // still tell the user the file has been deleted. this is only for admins.
                $notice .= "\n". __gettext('The file has been deleted');
            }
            else {
                $notice .= "\n". __gettext('The file could not be deleted');
            }
        }
    }
    return $notice;
}

/**
 * Replaces the given file with a string.
 *
 * The replacement string is used to notify that the original file had a virus
 * This is to avoid missing files but could result in the wrong content-type.
 * @param string $file Full path to the file.
 * @return boolean
 */
function clam_replace_infected_file($file) {
    $newcontents = __gettext('This file that has been uploaded was found to contain a virus and has been moved or delted and the user notified.');
    if (!$f = fopen($file, 'w')) {
        return false;
    }
    if (!fwrite($f, $newcontents)) {
        return false;
    }
    return true;
}


/**
 * If $CFG->runclamonupload is set, we scan a given file. (called from {@link preprocess_files()})
 *
 * This function will add on a uploadlog index in $file.
 * @param mixed $file The file to scan from $files. or an absolute path to a file.
 * @return int 1 if good, 0 if something goes wrong (opposite from actual error code from clam)
 */ 
function clam_scan_file(&$file) {
    global $CFG, $USER;

    if (is_array($file) && is_uploaded_file($file['tmp_name'])) { // it's from $_FILES
        $appendlog = true; 
        $fullpath = $file['tmp_name'];
    }
    else if (file_exists($file)) { // it's a path to somewhere on the filesystem!
        $fullpath = $file;
    }
    else {
        return false; // erm, what is this supposed to be then, huh?
    }

    $CFG->pathtoclam = trim($CFG->pathtoclam);

    if (!$CFG->pathtoclam || !file_exists($CFG->pathtoclam) || !is_executable($CFG->pathtoclam)) {
        $newreturn = 1;
        $notice = sprintf(__gettext('Elgg is configured to run clam on file upload, but the path supplied to Clam AV, %s,  is invalid.'), $CFG->pathtoclam);
        if ($CFG->clamfailureonupload == 'actlikevirus') {
            $notice .= "\n". __gettext('In addition, Elgg is configured so that if clam fails to run, files are treated like viruses.  This essentially means that no student can upload a file successfully until you fix this.');
            $notice .= "\n". clam_handle_infected_file($fullpath);
            $newreturn = false; 
        }
        clam_mail_admins($notice);
        if ($appendlog) {
            $file['uploadlog'] .= "\n". __gettext('Your administrator has enabled virus checking for file uploads but has misconfigured something.<br />Your file upload was NOT successful. Your administrator has been emailed to notify them so they can fix it.<br />Maybe try uploading this file later.');
            $file['clam'] = 1;
        }
        return $newreturn; // return 1 if we're allowing clam failures
    }
    
    $cmd = $CFG->pathtoclam .' '. $fullpath ." 2>&1";
    
    // before we do anything we need to change perms so that clamscan can read the file (clamdscan won't work otherwise)
    chmod($fullpath,0644);
    
    exec($cmd, $output, $return);
    
    switch ($return) {
    case 0: // glee! we're ok.
        return 1; // translate clam return code into reasonable return code consistent with everything else.
    case 1:  // bad wicked evil, we have a virus.
        $info->user = $USER->name;
        $notice = sprintf(__gettext('Attention administrator! Clam AV has found a virus in a file uploaded by %s. Here is the output of clamscan:'), $info->user);
        $notice .= "\n\n". implode("\n", $output);
        $notice .= "\n\n". clam_handle_infected_file($fullpath); 
        clam_mail_admins($notice);
        if ($appendlog) {
            $info->filename = $file['originalname'];
            $file['uploadlog'] .= "\n". sprintf(__gettext('The file you have uploaded, %s, has been scanned by a virus checker and found to be infected! Your file upload was NOT successful.'), $info->filename);
            $file['virus'] = 1;
        }
        return false; // in this case, 0 means bad.
    default: 
        // error - clam failed to run or something went wrong
        $notice .= sprintf(__gettext('Clam AV has failed to run.  The return error message was %s. Here is the output from Clam:'), get_clam_error_code($return));
        $notice .= "\n\n". implode("\n", $output);
        $newreturn = true;
        if ($CFG->clamfailureonupload == 'actlikevirus') {
            $notice .= "\n". clam_handle_infected_file($fullpath);
            $newreturn = false;
        }
        clam_mail_admins($notice);
        if ($appendlog) {
            $file['uploadlog'] .= "\n". __gettext('Your administrator has enabled virus checking for file uploads but has misconfigured something.<br />Your file upload was NOT successful. Your administrator has been emailed to notify them so they can fix it.<br />Maybe try uploading this file later.');
            $file['clam'] = 1;
        }
        return $newreturn; // return 1 if we're allowing failures.
    }
}

/**
 * Emails admins about a clam outcome
 *
 * @param string $notice The body of the email to be sent.
 */
function clam_mail_admins($notice) {
    
    global $CFG;
        
    $subject = sprintf(__gettext('%s :: Clam AV notification'), $CFG->sitename);
    $user = new StdClass;
    $user->email = $CFG->sysadminemail;
    $user->name = $CFG->sitename.' '.__gettext('Administrator');
    email_to_user($user,$user,$subject,$notice);
    /*
    $admins = get_admins();
    foreach ($admins as $admin) {
        email_to_user($admin, get_admin(), $subject, $notice);
    }
    */
}


/**
 * Returns the string equivalent of a numeric clam error code
 *
 * @param int $returncode The numeric error code in question.
 * return string The definition of the error code
 */
function get_clam_error_code($returncode) {
    $returncodes = array();
    $returncodes[0] = 'No virus found.';
    $returncodes[1] = 'Virus(es) found.';
    $returncodes[2] = ' An error occured'; // specific to clamdscan
    // all after here are specific to clamscan
    $returncodes[40] = 'Unknown option passed.';
    $returncodes[50] = 'Database initialization error.';
    $returncodes[52] = 'Not supported file type.';
    $returncodes[53] = 'Can\'t open directory.';
    $returncodes[54] = 'Can\'t open file. (ofm)';
    $returncodes[55] = 'Error reading file. (ofm)';
    $returncodes[56] = 'Can\'t stat input file / directory.';
    $returncodes[57] = 'Can\'t get absolute path name of current working directory.';
    $returncodes[58] = 'I/O error, please check your filesystem.';
    $returncodes[59] = 'Can\'t get information about current user from /etc/passwd.';
    $returncodes[60] = 'Can\'t get information about user \'clamav\' (default name) from /etc/passwd.';
    $returncodes[61] = 'Can\'t fork.'; 
    $returncodes[63] = 'Can\'t create temporary files/directories (check permissions).';
    $returncodes[64] = 'Can\'t write to temporary directory (please specify another one).';
    $returncodes[70] = 'Can\'t allocate and clear memory (calloc).';
    $returncodes[71] = 'Can\'t allocate memory (malloc).';
    if ($returncodes[$returncode])
       return $returncodes[$returncode];
    return __gettext('There was an unknown error with clam.');

}

/**
 * Adds a file upload to the log table so that clam can resolve the filename to the user later if necessary
 *
 * @uses $CFG
 * @uses $USER
 * @param string $newfilepath ?
 * @param boolean $nourl ?
 * @todo Finish documenting this function
 */
function clam_log_upload($newfilepath, $nourl=false) {
    global $CFG, $USER;
    // get rid of any double // that might have appeared
    $newfilepath = preg_replace('/\/\//', '/', $newfilepath);
    if (strpos($newfilepath, $CFG->dataroot) === false) {
        $newfilepath = $CFG->dataroot . $newfilepath;
    }
    $courseid = 0;
    //TODO fixme no course (Penny)
    //    add_to_log($courseid, 'upload', 'upload', ((!$nourl) ? substr($_SERVER['HTTP_REFERER'], 0, 100) : ''), $newfilepath);
}

/**
 * This function logs to error_log and to the log table that an infected file has been found and what's happened to it.
 *
 * @param string $oldfilepath Full path to the infected file before it was moved.
 * @param string $newfilepath Full path to the infected file since it was moved to the quarantine directory (if the file was deleted, leave empty).
 * @param int $userid The user id of the user who uploaded the file.
 */
function clam_log_infected($oldfilepath='', $newfilepath='', $userid=0) {

    //    add_to_log(0, 'upload', 'infected', $_SERVER['HTTP_REFERER'], $oldfilepath, 0, $userid);//TODO fixme (Penny)
    
    $user = get_record('users', 'ident', $userid);
    
    $errorstr = 'Clam AV has found a file that is infected with a virus. It was uploaded by '
        . ((empty($user)) ? ' an unknown user ' : $user->name)
        . ((empty($oldfilepath)) ? '. The infected file was caught on upload ('.$oldfilepath.')' 
           : '. The original file path of the infected file was '. $oldfilepath)
        . ((empty($newfilepath)) ? '. The file has been deleted ' : '. The file has been moved to a quarantine directory and the new path is '. $newfilepath);

    error_log($errorstr);
}


/**
 * Create a directory.
 *
 * @uses $CFG
 * @param string $directory  a string of directory names under $CFG->dataroot eg  stuff/assignment/1
 * param boolean $shownotices If true then notification messages will be printed out on error.
 * @return string|false Returns full path to directory if successful, false if not
 */
function make_upload_directory($directory, $shownotices=true) {
    
    global $CFG;
    
    $currdir = $CFG->dataroot;
    
    umask(0000);
    
    if (!file_exists($currdir)) {
        if (! mkdir($currdir, $CFG->directorypermissions)) {
            if ($shownotices) {
                notify('ERROR: You need to create the directory '. $currdir .' with web server write access');
            }
            return false;
        }
        if ($handle = fopen($currdir.'/.htaccess', 'w')) {   // For safety
            @fwrite($handle, "deny from all\r\n");
            @fclose($handle);
        }
    }
    
    $dirarray = explode('/', $directory);
    
    foreach ($dirarray as $dir) {
        $currdir = $currdir .'/'. $dir;
        if (! file_exists($currdir)) {
            if (! mkdir($currdir, $CFG->directorypermissions)) {
                if ($shownotices) {
                    notify('ERROR: Could not find or create a directory ('. $currdir .')');
                }
                return false;
            }
            //@chmod($currdir, $CFG->directorypermissions);  // Just in case mkdir didn't do it
        }
    }
    
    return $currdir;
}



?>