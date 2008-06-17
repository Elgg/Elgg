<?php
global $USER;
global $CFG;
global $page_owner;

if (isset($parameter) && $page_owner != -1) {
    if (!is_array($parameter)) {
        switch($parameter) {

        case    "profile":
            if (record_exists('users','ident',$page_owner,'owner',$USER->ident,'user_type','community')) {
                $run_result = true;
            }
            break;
        case    "files":
        case    "weblog":
            if (record_exists('users','ident',$page_owner,'owner',$USER->ident,'user_type','community')) {
                $run_result = true;
            }
            if (empty($run_result)) {
                if(run('community:membership',array($page_owner,$USER->ident))>0){
                    $run_result = true;
                }
            }
            break;
        case     "uploadicons":
            if (record_exists('users','ident',$page_owner,'owner',$USER->ident,'user_type','community')) {
                $run_result = true;
            }
            break;
        case    "userdetails:change":
            if (record_exists('users','ident',$page_owner,'owner',$USER->ident,'user_type','community')) {
                $run_result = true;
            }
            break;
        }
    } else {
        switch($parameter[0]) {

        case    "files:edit":
        case    "weblog:edit":
	  // we need to know 2 things about file or post: its owner and the community it is
	  // posted to.
            $owner = $parameter[1];
	    if(isset($parameter[2])){
	      $weblog=$parameter[2];
	    } else {
	      $weblog=0;
	    }
	    // Here we only check wether the logged on user is the moderator of the community 
	    // where the posts was made or file was uploaded ($weblog).
	    // Permission to edit their own content is granted in mod/blog/lib/permission_check.php and
	    // mod/file/lib/permission_check.php.
            if (record_exists('users','ident',$weblog,'owner',$USER->ident,'user_type','community')) {
                $run_result = true;
            }
            
            break;
        case    "userdetails:change":
            if (record_exists('users','ident',$parameter[1],'owner',$USER->ident,'user_type','community')) {
                $run_result = true;
            }
            break;
        }
    }
}

?>