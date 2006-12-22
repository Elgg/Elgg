<?php
/** library of functions to deal with lms integration
*/

// constants for validation
define('LMS_NO_SUCH_HOST','NO SUCH LMSHOST');
define('LMS_NO_SUCH_USER','NO SUCH USER');
define('LMS_INVALID_HASH','INVALID HASH');
define('LMS_INVALID_NETWORK','INVALID IP ADDRESS');
define('LMS_SNOOPY_USER_AGENT','Elgg/LMS integration');

require_once($CFG->dirroot . 'lib/snoopy/Snoopy.class.inc');

function find_lms_user($installid,$username,$signature,$confirmaction=null,$firstname=null,$lastname=null,$email=null) {
    global $CFG;
    // find this host from the installid
    if (empty($CFG->lmshosts) || !is_array($CFG->lmshosts) || !array_key_exists($installid,$CFG->lmshosts)) {
        return LMS_NO_SUCH_HOST;
    }
    $host = $CFG->lmshosts[$installid];
    
    // validate our md5 hash
    if ($confirmaction == 'signupconfirmation') {
        $stringtohash = $installid.'|'.$username.'|'.$firstname.'|'.$lastname.'|'.$email.'|'.$host['token'];
    } else {
        $stringtohash = $installid.'|'.$username.'|'.$host['token']; 
        // firstname, lastname and email cannot be relied upon not to change
        // so we only want to add them to the hash on signup, not for auth or anything else.
    }
    $checksig = md5($stringtohash);
    if ($checksig != $signature) {
        return LMS_INVALID_HASH;
    }
    
    // if we have an ip address, check it.
    if (array_key_exists('networkaddress',$host) && empty($confirmaction)) {
        if (!address_in_subnet(getremoteaddr(),$host['networkaddress'])) {
            return LMS_INVALID_NETWORK;
        }
    }   

    if (!empty($confirmaction) && !empty($host['confirmurl'])) {
        $client = new Snoopy();
        $client->agent = LMS_SNOOPY_USER_AGENT;
        $client->read_timeout = 5;
        $client->use_gzip = true;
        $postdata = array('action' => $confirmaction, 'username' => $username, 'signature' => $signature);
        @$client->submit($host['confirmurl'],$postdata);
        if ($client->results != 'OK') {
            return clean_param($client->results,PARAM_CLEAN);
        }
    }

    // find our user (we only want to check username and installid, the others could potentially change..
    if (!$user = get_record_sql('SELECT u.* FROM '.$CFG->prefix.'users u 
                        JOIN '.$CFG->prefix.'users_alias ua ON ua.user_id = u.ident
                        WHERE ua.installid = ? AND ua.username = ?',array($installid,$username))) { 
        return LMS_NO_SUCH_USER;
    }
    return $user;
}

function lms_get_folder($installid,$foldername,$user) {
    
    // look for the installid folder first.
    if (!$folder = get_record('file_folders','owner',$user->ident,'name',$installid)) {
         // we have to make it.
         $folder = new StdClass;
         $folder->name = $installid;
         $folder->owner = $user->ident;
         $folder->files_owner = $user->ident;
         $folder->parent = -1;
         $folder->access = 'user'.$user->ident; // ew
         $folder->ident = insert_record('file_folders',$folder);
     }
    
    if (!$subfolder = get_record('file_folders','owner',$user->ident,'name',$foldername,'parent',$folder->ident)) {
         // we have to make it.
         $subfolder = new StdClass;
         $subfolder->name = $foldername;
         $subfolder->owner = $user->ident;
         $subfolder->files_owner = $user->ident;
         $subfolder->parent = $folder->ident;
         $subfolder->access = 'user'.$user->ident; // ew
         $subfolder->ident = insert_record('file_folders',$subfolder);
    }

    return $subfolder;
}

?>